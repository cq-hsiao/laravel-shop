<?php

namespace App\Services;

use App\Exceptions\CouponCodeUnavailableException;
use App\Exceptions\InternalException;
use App\Jobs\RefundInstallmentOrder;
use App\Models\CouponCode;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\ProductSku;
use App\Exceptions\InvalidRequestException;
use App\Jobs\CloseOrder;
use Carbon\Carbon;
use Elasticsearch\Endpoints\Indices\Close;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Yansongda\Pay\Exceptions\GatewayException;

class OrderService
{
    public function store(User $user, UserAddress $address, $remark, $items,CouponCode $coupon = null)
    {
        // 如果传入了优惠券，则先检查是否可用
        if ($coupon) {
            // 但此时我们还没有计算出订单总金额，因此先不校验
            $coupon->checkCouponAvailable($user);
        }

        // 开启一个数据库事务
        $order = DB::transaction(function () use ($user, $address, $remark, $items ,$coupon) {
            // 更新此地址的最后使用时间
            $address->update(['last_used_at' => Carbon::now()]);
            // 创建一个订单
            $order   = new Order([
                'address'      => [ // 将地址信息放入订单中
                    'address'       => $address->full_address,
                    'zip'           => $address->zip,
                    'contact_name'  => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark'       => $remark,
                'total_amount' => 0,
                'type' => Order::TYPE_NORMAL,
            ]);
            // 订单关联到当前用户
            $order->user()->associate($user);
            // 写入数据库
            $order->save();

            $totalAmount = 0;
            // 遍历用户提交的 SKU
            foreach ($items as $data) {
                $sku  = ProductSku::find($data['sku_id']);
                // 创建一个 OrderItem 并直接与当前订单关联
                $item = $order->items()->make([
                    'amount' => $data['amount'],
                    'price'  => $sku->price,
                ]);
                $item->product()->associate($sku->product_id);
                $item->productSku()->associate($sku);
                $item->save();
                $totalAmount += $sku->price * $data['amount'];
                if ($sku->decreaseStock($data['amount']) <= 0) {
                    throw new InvalidRequestException('该商品库存不足');
                }
            }

            // 使用了优惠券
            if($coupon) {
                // 总金额已经计算出来了，检查是否符合优惠券规则
                $coupon->checkCouponAvailable($user,$totalAmount);
                // 把订单金额修改为优惠后的金额
                $totalAmount = $coupon->getAdjustedPrice($totalAmount);
                // 将订单与优惠券关联
                $order->couponCode()->associate($coupon);
                // 增加优惠券的用量，需判断返回值
                if ($coupon->changeUsed() <= 0) {
                    throw new CouponCodeUnavailableException('该优惠券已被兑完');
                }
            }

            // 更新订单总金额
            $order->update(['total_amount' => $totalAmount]);

            // 将下单的商品从购物车中移除
            $skuIds = collect($items)->pluck('sku_id')->all();
            app(CartService::class)->remove($skuIds);

            return $order;
        });

        // 这里我们直接使用 dispatch 函数
        dispatch(new CloseOrder($order, config('app.order_ttl')));

        return $order;
    }

    //  crowdfunding 方法用于实现众筹商品下单逻辑
    public function crowdfunding(User $user,UserAddress $address,ProductSku $productSku,$amount)
    {
        // 开始事务
        $order = DB::transaction(function () use ($user,$address,$productSku,$amount){
            // 更新地址最后使用时间
            $address->update(['last_used_at' => Carbon::now()]);

            // 创建一个订单
            $order = new Order([
                'address' => [
                   'address' => $address->full_address,
                   'zip' => $address->zip,
                   'contact_name'  => $address->contact_name,
                   'contact_phone' => $address->contact_phone,
                ],
                'remark'       => '',
                'total_amount' => $productSku->price * $amount,
                'type' => Order::TYPE_CROWDFUNDING,
            ]);

            // 订单关联到当前用户
            $order->user()->associate($user);
            // 写入数据库
            $order->save();
            // 创建一个新的订单项并与 SKU 关联
            $item = $order->items()->make([
                'amount' => $amount,
                'price'  => $productSku->price,
            ]);

            $item->product()->associate($productSku->product_id);
            $item->productSku()->associate($productSku);
            $item->save();

            // 扣减对应 SKU 库存
            if($productSku->decreaseStock($amount) <= 0)
            {
                throw new InvalidRequestException('该商品库存不足');
            }

            return $order;
        });

        // 众筹结束时间减去当前时间得到剩余秒数
        $crowdfundingTtl = $productSku->product->crowdfunding->end_at->getTimestamp() - time();
        // 剩余秒数与默认订单关闭时间取较小值作为订单关闭时间
        dispatch(new CloseOrder($order, min(config('app.order_ttl'), $crowdfundingTtl)));
        return $order;
    }

    // 订单退款逻辑
    public function refundOrder(Order $order)
    {
        // 判断该订单的支付方式
        switch ($order->payment_method){
            case 'alipay':
                $refundNo = Order::getAvailableRefundNo();
                // 调用支付宝支付实例的 refund 方法

                try{
                    $res = app('alipay')->refund([
                        'out_trade_no'   => $order->no,          // 之前的订单流水号
                        'refund_amount'  => $order->total_amount,// 退款金额，单位元
                        'out_request_no' => $refundNo,           // 退款订单号
                    ]);
                }catch (GatewayException $e){
                    $extra = $order->extra;
                    $extra['refund_failed_info'] = $e->getMessage();
                    $order->update([
                        'refund_status' => Order::REFUND_STATUS_APPLIED,
                        'refund_no'     => $refundNo,
                        'extra'         => $extra,
                    ]);

                    throw new InternalException('退款失败：'.$extra['refund_failed_info'],'退款失败：'.$extra['refund_failed_info']);
                }

                // 根据支付宝的文档，如果返回值里有 sub_code 字段说明退款失败
                if($res->sub_code){
                    // 将退款失败存入extra字段
                    $extra = $order->extra;
                    $extra['refund_failed_code'] = $res->sub_code;
                    // 将订单的退款状态标记为退款失败
                    $order->update([
                        'extra'         => $extra,
                        'refund_status' => Order::REFUND_STATUS_FAILED,
                        'refund_no'     => $refundNo
                    ]);

                } else {
                    // 将订单的退款状态标记为退款成功并保存退款订单号
                    $order->update([
                        'refund_status' => Order::REFUND_STATUS_SUCCESS,
                        'refund_no'     => $refundNo
                    ]);
                }
                break;
            case 'wechat':

                // 生成退款订单号
                $refundNo = Order::getAvailableRefundNo();
                app('wechat_pay')->refund([
                    'out_trade_no' => $order->no, // 之前的订单流水号
                    'total_fee' => $order->total_amount * 100, //原订单金额，单位分
                    'refund_fee' => $order->total_amount * 100, // 要退款的订单金额，单位分
                    'out_refund_no' => $refundNo, // 退款订单号
                    // 微信支付的退款结果并不是实时返回的，而是通过退款回调来通知，因此这里需要配上退款回调接口地址
                    'notify_url' => ngrok_url('payment.wechat.refund_notify') // 由于是开发环境，需要配成 requestbin 地址
                ]);
                // 将订单状态改成退款中
                $order->update([
                    'refund_no' => $refundNo,
                    'refund_status' => Order::REFUND_STATUS_PROCESSING,
                ]);
                break;

            case Order::PAYMENT_INSTALLMENT :
                $order->update([
                    'refund_no' => Order::getAvailableRefundNo(), // 生成退款订单号
                    'refund_status' => Order::REFUND_STATUS_PROCESSING, // 将退款状态改为退款中
                ]);
                // 触发退款异步任务
                dispatch(new RefundInstallmentOrder($order));
                break;

            default:
                // 原则上不可能出现，这个只是为了代码健壮性
                throw new InternalException('未知订单支付方式：'.$order->payment_method);
                break;
        }
    }

    public function seckill(User $user, array $addressData, ProductSku $sku)
    {
        $order = DB::transaction(function () use ($user,$addressData,$sku) {
            // 更新此地址的最后使用时间
//            $address->update(['last_used-at' => Carbon::now()]);

            // 扣减对应 SKU 库存
            if($sku->decreaseStock(1) <= 0) {
                throw new InvalidRequestException('该商品库存不足');
            }

            // 创建一个订单
            $order = new Order([
                'address'      => [ // address 字段直接从 $addressData 数组中读取
                    'address'       => $addressData['province'].$addressData['city'].$addressData['district'].$addressData['address'],
                    'zip'           => $addressData['zip'],
                    'contact_name'  => $addressData['contact_name'],
                    'contact_phone' => $addressData['contact_phone'],
                ],
                'remark'       => '',
                'total_amount' => $sku->price,
                'type'         => Order::TYPE_SECKILL,
            ]);

            // 订单关联到当前用户
            $order->user()->associate($user);
            // 写入数据库
            $order->save();

            // 创建一个新的订单项并与 SKU 关联
            $item = $order->items()->make([
                'amount' => 1, // 秒杀商品只能一份
                'price'  => $sku->price,
            ]);
            $item->product()->associate($sku->product_id);
            $item->productSku()->associate($sku);
            $item->save();

            Redis::decr('seckill_sku_'.$sku->id);

            return $order;
        });

        // 秒杀订单的自动关闭时间与普通订单不同
        dispatch(new CloseOrder($order,config('app.seckill_order_ttl' , 600)));

        return $order;
    }
}
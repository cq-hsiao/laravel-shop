<?php

namespace App\Http\Controllers;

use App\Exceptions\InternalException;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\OrderRequest;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSku;
use App\Models\UserAddress;
use App\Services\CartService;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{

    public function index(Request $request)
    {
//        $orders = Order::query()
//            // 使用 with 方法预加载，避免N + 1问题
//            ->with(['items.product', 'items.productSku'])
//            ->where('user_id', $request->user()->id)
//            ->orderBy('created_at', 'desc')
//            ->paginate();

        $orders = $request->user()->orders()
            ->with(['items.product','items.productSku'])
            ->orderBy('created_at','desc')
            ->paginate();
        
        return view('orders.index',['orders' => $orders]);
    }

    public function show(Order $order,Request $request){

        try{
            $this->authorize('own', $order);
        }catch (AuthorizationException $e){
            throw new InvalidRequestException('抱歉，权限不足~');
        }

        // 延迟预加载，不同点在于 load() 是在已经查询出来的模型上调用，而 with() 则是在 ORM 查询构造器上调用。
         return view('orders.show',['order' => $order->load('items.product','items.productSku')]);

//         dd($order->with(['items.product','items.productSku'])->where('id',$order->id)->first());
//        return view('orders.show',['order' => $order->with(['items.product','items.productSku'])->where('id',$order->id)->first()]);
//        return view('orders.show',['order' => Order::query()->with(['items.product','items.productSku'])->where(['id'=>$order->id])->first()]);

    }


    public function store(OrderRequest $request, OrderService $orderService)
    {
        $user    = $request->user();
        $address = UserAddress::find($request->input('address_id'));

        return $orderService->store($user, $address, $request->input('remark'), $request->input('items'));
    }



    public function old_store(OrderRequest $request,CartService $cartService)
    {
        $user = $request->user();

        // 开启一个数据库事务
        $order = DB::transaction(function () use ($user,$request, $cartService){

            $address = UserAddress::query()->find($request->input('address_id'));

            // 更新此地址的最后使用时间
            $address->update(['last_used_at',Carbon::now()]);

            // 创建一个订单
            $order = new Order([
                'address'      => [ // 将地址信息放入订单中
                    'address'       => $address->full_address,
                    'zip'           => $address->zip,
                    'contact_name'  => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark'       => $request->input('remark'),
                'total_amount' => 0,
            ]);

            // 订单关联到当前用户
            $order->user()->associate($user);
            // 写入数据库
            $order->save();

            $totalAmount = 0;
            $items       = $request->input('items');
            // 遍历用户提交的 SKU
            foreach ($items as $data){
                $sku = ProductSku::query()->find($data['sku_id']);
                // 创建一个 OrderItem 并直接与当前订单关联
                $item = $order->items()->make([
                    'amount' => $data['amount'],
                    'price'  => $sku->price,
                ]);
                //$item = new OrderItem(); $item->order()->associate($order);

                $item->product()->associate($sku->product_id);
                $item->productSku()->associate($sku);

                $item->save();
                $totalAmount += $sku->price * $data['amount'];
                if($sku->decreaseStock($data['amount']) <= 0)
                {
                    throw new InvalidRequestException('该商品库存不足');
                }
            }

            // 更新订单总金额
            $order->update(['total_amount' => $totalAmount]);

            // 将下单的商品从购物车中移除
//            $skuIds = collect($items)->pluck('sku_id');
//            $user->cartItems()->whereIn('product_sku_id', $skuIds)->delete();
            $skuIds = collect($request->input('items'))->pluck('sku_id')->all();
            $cartService->remove($skuIds);

            return $order;
        });

        // 创建订单之后触发任务
        $this->dispatch(new CloseOrder($order,config('app.order_ttl')));

        return $order;
    }
}

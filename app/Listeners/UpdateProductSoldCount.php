<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Models\OrderItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;


//  implements ShouldQueue 代表此监听器是异步执行的
class UpdateProductSoldCount implements ShouldQueue
{
    // Laravel 会默认执行监听器的 handle 方法，触发的事件会作为 handle 方法的参数
    public function handle(OrderPaid $event)
    {
        // 从事件对象中取出对应的订单
        $order = $event->getOrder();

        // 预加载商品数据
        $order->load('items.product');

        // 循环遍历订单的商品
        foreach ($order->items as $item){
            $product = $item->product;
            // 计算对应商品的销量
//            $soldCount = OrderItem::query()
//                ->where('product_id',$product->id)
//                ->whereHas('order',function ($query){
//                    $query->whereNotNull('paid_at'); // 关联的订单状态是已支付
//                })->sum('amount');

            $soldCountQuery = OrderItem::query()
                ->where('product_id',$product->id)
                ->whereHas('order',function ($query){
                    $query->whereNotNull('paid_at'); // 关联的订单状态是已支付
                });

            $soldCount = $soldCountQuery->sum('amount');

            Log::info('add_soldNum_sql',[$soldCountQuery->toSql()]);

            // 更新商品销量
            $product->update([
                'sold_count' => $soldCount,
            ]);
        }
    }
}

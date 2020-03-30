<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateCrowdfundingProductProgress implements ShouldQueue
{

    public function __construct()
    {
        DB::listen(function($query){
            Log::info(get_class(),[$query->sql]);
        });
    }

    /**
     * Handle the event.
     *
     * @param  OrderPaid  $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        $order = $event->getOrder();
        // 如果订单类型不是众筹商品订单，无需处理
        if ($order->type !== Order::TYPE_CROWDFUNDING) {
            return;
        }

        $crowdfunding = $order->items()->first()->product->crowdfunding;

        $data = Order::query()
            // 1、查出订单类型为众筹订单 2、并且是已支付的 3、并且包含了本商品
            ->where('type',Order::TYPE_CROWDFUNDING)
            ->whereNotNull('paid_at')
            ->whereHas('items',function($query) use ($crowdfunding) {
                $query->where('product_id',$crowdfunding->product_id);
            })
            ->first([
                // 取出订单总金额,去重的支持用户数
               DB::raw('sum(total_amount) as total_amount'),
               DB::raw('count(distinct(user_id)) as user_count'),
            ]);

        $crowdfunding->update([
            'total_amount' => $data->total_amount,
            'user_count'   => $data->user_count,
        ]);
    }
}

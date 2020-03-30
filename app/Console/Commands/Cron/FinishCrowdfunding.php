<?php

namespace App\Console\Commands\Cron;

use App\Jobs\RefundCrowdfundingOrders;
use App\Models\CrowdfundingProduct;
use App\Models\Order;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FinishCrowdfunding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:finish-crowdfunding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '结束众筹';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        CrowdfundingProduct::query()
            // 众筹结束时间早于当前时间 众筹状态为众筹中
            ->where([['end_at','<=',Carbon::now()],['status','=',CrowdfundingProduct::STATUS_FUNDING]])
            ->get()
            ->each(function (CrowdfundingProduct $crowdfunding){
                // 如果众筹目标金额大于实际众筹金额,调用众筹失败逻辑,否则调用众筹成功逻辑
               if($crowdfunding->target_amount > $crowdfunding->total_amount){
                   $this->crowdfundingFailed($crowdfunding);
               } else {
                   $this->crowdfundingSucceed($crowdfunding);
               }
            });
    }

    protected function crowdfundingSucceed(CrowdfundingProduct $crowdfunding)
    {
        // 只需将众筹状态改为众筹成功即可
        $crowdfunding->update([
            'status' => CrowdfundingProduct::STATUS_SUCCESS,
        ]);
    }

    protected function crowdfundingFailed(CrowdfundingProduct $crowdfunding)
    {
        // 将众筹状态改为众筹失败
        $crowdfunding->update([
            'status' => CrowdfundingProduct::STATUS_FAIL,
        ]);

        dispatch(new RefundCrowdfundingOrders($crowdfunding));

//        $orderService = app(OrderService::class);
//        // 查询出所有参与了此众筹的订单
//        Order::query()
//            ->where('type',Order::TYPE_CROWDFUNDING)
//            ->whereNotNull('paid_at')
//            ->whereHas('items',function($query) use ($crowdfunding){
//                // 包含了该商品
//                $query->where('product_id',$crowdfunding->product_id);
//            })
//            ->get()
//            ->each(function(Order $order) use ($orderService){
//                $orderService->refundOrder($order);
//            });
    }
}

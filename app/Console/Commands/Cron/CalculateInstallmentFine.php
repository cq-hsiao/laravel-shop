<?php

namespace App\Console\Commands\Cron;

use App\Models\Installment;
use App\Models\InstallmentItem;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalculateInstallmentFine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:calculate-installment-fine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '计算分期付款逾期费';

    public function __construct()
    {
        DB::listen(function($query){
            Log::info('计算分期付款逾期费sql',[$query->sql]);
        });
        parent::__construct();
    }


    public function handle()
    {
       InstallmentItem::query()
           // 预加载分期付款数据，避免 N + 1 问题
           ->with(['installment'])
           ->whereHas('installment',function ($query){
               // 对应的分期状态为还款中
               $query->where('status',Installment::STATUS_REPAYING);
           })
           // 还款截止日期在当前时间之前
           ->where('due_date','<=',Carbon::now())
           ->whereNull('paid_at')
            // 使用 chunkById 避免一次性查询太多记录
           ->chunkById(1000,function($items){
                // 遍历查询出来的还款计划
                foreach ($items as $item) {
                    // 通过 Carbon 对象的 diffInDays 直接得到逾期天数
                    $overdueDays = Carbon::now()->diffInDays($item->due_date);
                    // 本金与手续费之和
                    $total_amount = big_number($item->base)->add($item->fee)->getValue();
                    // 计算逾期费
                    $fine = big_number($total_amount)
                        ->multiply($overdueDays)
                        ->multiply($item->installment->fine_rate)
                        ->divide(100)
                        ->getValue();
                    // 避免逾期费高于本金与手续费之和，使用 compareTo 方法来判断
                    // 如果 $fine 大于 $base，则 compareTo 会返回 1，相等返回 0，小于返回 -1
                    $fine = big_number($fine)->compareTo($total_amount) === 1 ? $total_amount : $fine;
                    $item->update([
                        'fine' => $fine,
                    ]);
                }
            });
    }
}

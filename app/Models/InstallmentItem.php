<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Moontoast\Math\BigNumber;

class InstallmentItem extends Model
{
    const REFUND_STATUS_PENDING = 'pending';
    const REFUND_STATUS_PROCESSING = 'processing';
    const REFUND_STATUS_SUCCESS = 'success';
    const REFUND_STATUS_FAILED = 'failed';

    public static $refundStatusMap = [
        self::REFUND_STATUS_PENDING    => '未退款',
        self::REFUND_STATUS_PROCESSING => '退款中',
        self::REFUND_STATUS_SUCCESS    => '退款成功',
        self::REFUND_STATUS_FAILED     => '退款失败',
    ];

    protected $fillable = ['sequence','base','fee','fine','due_date','paid_at','payment_method','payment_no','refund_status'];
    protected $dates = ['paid_at','due_date'];


    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }

    // 创建一个访问器，返回当前还款计划需还款的总金额
    // \Moontoast\Math\BigNumber 这个类，这个类的构造函数接受两个参数，第一个参数就是我们要参与运算的数值，第二个参数是可选参数，用于表示我们希望的计算精度（即精确到小数点后几位）。
    // 同时这个类提供了许多常见的算术运算方法，比如 加法 add()、减法 subtract()、乘法 multiply()、除法 divide() 等等。
    public function getTotalAttribute()
    {
        // 小数点计算需要用 bcmath 扩展提供的函数
        $total = big_number($this->base)->add($this->fee);
        if (!is_null($this->fine)) {
            $total->add($this->fine);
        }

        return $total->getValue();
    }

    // 创建一个访问器，返回当前还款计划是否已经逾期
    public function getIsOverdueAttribute()
    {
        return Carbon::now()->gt($this->due_date);
    }
}

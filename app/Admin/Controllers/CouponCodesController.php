<?php

namespace App\Admin\Controllers;

use App\Models\CouponCode;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CouponCodesController extends AdminController
{

    public function index(Content $content)
    {
        return $content
            ->header('优惠券列表')
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CouponCode);

        // 默认按创建时间倒序排序
        $grid->model()->orderBy('created_at', 'desc');
        $grid->id('ID')->sortable();
        $grid->name('名称');
        $grid->code('优惠码');
//        $grid->type('类型')->display(function($value) {
//            return CouponCode::$typeMap[$value];
//        });
//        // 根据不同的折扣类型用对应的方式来展示
//        $grid->value('折扣')->display(function($value) {
//            return $this->type === CouponCode::TYPE_FIXED ? '￥'.$value : $value.'%';
//        });
        $grid->description('描述');
//        $grid->min_amount('最低金额');
//        $grid->total('总量');
//        $grid->used('已用');
        $grid->column('usage', '用量')->display(function ($value) {
            return "{$this->used} / {$this->total}";
        });
        $grid->enabled('是否启用')->sortable()->display(function($value) {
            return $value ? '是' : '否';
        });
        $grid->created_at('创建时间');

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        return $grid;
    }




}

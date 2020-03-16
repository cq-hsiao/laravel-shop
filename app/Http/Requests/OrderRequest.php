<?php

namespace App\Http\Requests;

use App\Models\ProductSku;
 use Illuminate\Validation\Rule;

class OrderRequest extends Request
{

    public function rules()
    {
        return [
            // 判断用户提交的地址 ID 是否存在于数据库并且属于当前用户
            // 后面这个条件非常重要，否则恶意用户可以用不同的地址 ID 不断提交订单来遍历出平台所有用户的收货地址
            'address_id' => [
                'required',
                Rule::exists('user_addresses','id')->where('user_id',$this->user()->id)
            ],
            'items'  => ['required', 'array'],
            'items.*.sku_id' => [ // 检查 items 数组下每一个子数组的 sku_id 参数
                'required',
                function($attribute,$value,$fail){

                    // 获取当前索引
                    preg_match('/items\.(\d+)\.sku_id/', $attribute, $m);
                    $index = $m[1];
                    $name = trim($this->input('items')[$index]['name']) ?
                        '<span style="color: #cb1c16">'. $this->input('items')[$index]['name'] .'</span> ' : '';

                    if(!$sku = ProductSku::query()->find($value)){
                        return $fail($name.'该商品不存在');
                    }
                    if (!$sku->product->on_sale) {
                        return $fail($name.'该商品未上架');
                    }
                    if ($sku->stock === 0) {
                        return $fail($name.'该商品已售完');
                    }

                    // 根据索引找到用户所提交的购买数量
                    $amount = $this->input('items')[$index]['amount'];
                    if ($amount > 0 && $amount > $sku->stock) {
                        return $fail($name.'该商品库存不足');
                    }
                },
            ],
            'items.*.amount' => ['required', 'integer', 'min:1'],
        ];
    }


    public function messages()
    {
        return [
          'address_id.required' => '请选择收货地址',
          'items.required' => '请选择要购买的商品'
        ];
    }
}

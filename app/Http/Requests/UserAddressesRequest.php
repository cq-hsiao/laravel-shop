<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAddressesRequest extends Request {

    public function attributes()
    {
        return [
            'province'      => '省',
            'city'          => '城市',
            'district'      => '地区',
            'address'       => '详细地址',
            'zip'           => '邮编',
            'contact_name'  => '姓名',
            'contact_phone' => '电话',
        ];
    }

    public function rules()
    {
        return [
            'province'      => 'required',
            'city'          => 'required',
            'address'       => 'required',
            'zip'           => 'required|numeric|regex:/^[0-9]{6}$/',
            'contact_name'  => 'required|between:3,25|regex:/^[\x{4e00}-\x{9fa5}A-Za-z0-9\-\_]+$/u',
            'contact_phone' => 'required|regex:/^1[34578][0-9]{9}$/',
        ];
    }
}

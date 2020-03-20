<?php

namespace App\Http\Requests;


class ApplyRefundRequest extends Request
{

    public function rules()
    {
        return [
            'reason' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'reason' => '退款原因'
        ];
    }
}

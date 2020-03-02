<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Exception;
use Throwable;


class InvalidRequestException extends Exception
{
    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message, $code);
    }

    public function render(Request $request)
    {
        if($request->expectsJson())
        {
            // json() 方法第二个参数就是 Http 返回码
            return response()->json(['msg' => $this->message], $this->code);
        }

        return view('pages.error', ['msg' => $this->message]);

    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressesRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
    public function index(Request $request)
    {
        return view('user_addresses.index',[
            'addresses' => $request->user()->addresses
        ]);
    }

    public function create()
    {
        return view('user_addresses.create_and_edit',[
           'address' => new UserAddress()
        ]);
    }

    public function store(UserAddressesRequest $request)
    {
        $request->user()->addresses()->create(
            $request->only([
                'province',
                'city',
                'district',
                'address',
                'zip',
                'contact_name',
                'contact_phone',
            ])
        );

        session()->flash('index_info','新增收货地址成功！');
        return redirect()->route('user_addresses.index');
    }

    public function edit(UserAddress $address)
    {
        $this->authorize('own', $address);

        return view('user_addresses.create_and_edit',[
            'address' => $address
        ]);
    }

    public function update(UserAddress $address,UserAddressesRequest $request)
    {
        $this->authorize('own', $address);

        $address->update($request->only([
                'province',
                'city',
                'district',
                'address',
                'zip',
                'contact_name',
                'contact_phone'
            ])
        );

        session()->flash('index_info','修改收货地址成功~');
        return redirect()->route('user_addresses.index');
    }

    public function destroy(UserAddress $address)
    {
        $this->authorize('own', $address);

        $address->delete();

        session()->flash('index_alert','收货地址成功删除~');
//        return redirect(route('user_addresses.index'));
        return [];
    }
}

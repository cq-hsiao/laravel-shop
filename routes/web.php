<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/','PagesController@root')->name('root');

// 在之前的路由里加上一个 verify 参数
Auth::routes(['verify' => true]);

// auth 中间件代表需要登录，verified中间件代表需要经过邮箱验证
Route::group(['middleware' => ['auth', 'verified']], function() {

    Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
    Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
    Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');
    Route::get('user_addresses/{address}','UserAddressesController@edit')->name('user_addresses.edit');
    Route::put('user_addresses/{address}', 'UserAddressesController@update')->name('user_addresses.update');
    Route::delete('user_addresses/{address}','UserAddressesController@destroy')->name('user_addresses.destroy');

    Route::post('products/{product}/favorite', 'ProductsController@favor')->name('products.favor');
    Route::delete('products/{product}/favorite', 'ProductsController@disfavor')->name('products.disfavor');
    Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');

    Route::post('cart','CartController@add')->name('cart.add');
    Route::get('cart', 'CartController@index')->name('cart.index');
    Route::delete('cart/{productSku}', 'CartController@remove')->name('cart.remove');

    Route::get('orders', 'OrdersController@index')->name('orders.index');
    Route::post('orders','OrdersController@store')->name('orders.store');
});


Route::redirect('/', '/products')->name('root');
Route::get('products', 'ProductsController@index')->name('products.index');
Route::get('products/{product}', 'ProductsController@show')->name('products.show');

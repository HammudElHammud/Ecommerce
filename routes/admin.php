<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
 admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){




    Route::group(['namespace'=> 'Dashboard','middleware'=> 'auth:admin','prefix'=>'admin' ],function (){


         Route::get('/','DashboardController@index')->name('admin.dashboard');

         Route::group(['prefix' =>'settings' ],function(){

           Route::get('shipping-method/{type}','SettingController@editShoppingMethod')->name('edit.Shipping.methods');
           Route::put('shipping-method/{id}','SettingController@updateShoppingMethod')->name('update.Shipping.methods');

    });

});


        Route::group(['namespace'=> 'Dashboard','middleware' => 'guest:admin', 'prefix'=>'admin'],function (){

                Route::get('login','LoginController@login')->name('admin.login');
                 Route::post('login','LoginController@postLogin')->name('admin.post.login');

              });

});

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingsRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use DB;


class SettingController extends Controller
{
    public function editShoppingMethod($type)
    {

        /// type : free   inner    outer
        ///
        if ($type == 'free')
            $ShippingMethod = Setting::where('key', 'free_shipping_label')->first();

        elseif ($type == 'inner')
            $ShippingMethod = Setting::where('key', 'local_label')->first();
        elseif ($type == 'outer')
            $ShippingMethod = Setting::where('key', 'outer_label')->first();
        else
            $ShippingMethod = Setting::where('key', 'free_shipping_label')->first();

//        return $ShippingMethod;
        return view('dashboard.Settings.Shipping.edit',compact('ShippingMethod'));

    }

    public function updateShoppingMethod(Request $request, $id)
    {
        try{
            $shippingsMothed = Setting::find($id);
            DB::beginTransaction();
            $shippingsMothed -> update([
                'plain_value'=> $request->plain_value

            ]);

            $shippingsMothed -> value = $request->value;
            $shippingsMothed->save();
            DB::commit();
            return redirect()->back()->with(['success' => 'تم التحديث بنجاح']);


        }catch (\Exception $ex){
            return redirect()->back()->with(['error' => 'هناك خطا ما يرجا المحاولة في ما بعد   ']);
            DB::rollBack();



        }


    }
}

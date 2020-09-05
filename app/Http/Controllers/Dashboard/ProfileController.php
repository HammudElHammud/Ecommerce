<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Admin as Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function editProfile(){

        $admin = Admin::find(Auth('admin')->user()->id);


        return view('dashboard.Profile.edit',compact('admin'));

    }

    public function updateProfile(ProfileRequest $request){

        try{
            $admin = Admin::find(Auth('admin')->user()->id);
            $admin->update($request -> only(['email','name']));

            return redirect()->back()->with(['success' => 'تم التحديث بنجاح']);


        }catch (\Exception $ex){
            return redirect()->back()->with(['error' => 'هناك خطا ما يرجا المحاولة في ما بعد   ']);
            DB::rollBack();



        }


    }
}

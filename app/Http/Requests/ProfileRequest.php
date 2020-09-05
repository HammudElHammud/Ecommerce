<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:admin,email',
            'password' => 'required',
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => "{{__('admin/profile.email required')}} ",
//            'email.email' => '{{__("admin/profile.email email")}}',
//            'name.required' => '{{__("admin/profile.name")}}',
        ];
    }
 }

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|regex:/^[\pL\s]+$/',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
            'phone' => 'nullable|regex:/^[0-9 +\s]+$/',
            'profile_photo' => 'mimes:jpeg,jpg,png',
        ];
    }
}

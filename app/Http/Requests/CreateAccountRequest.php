<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateAccountRequest extends FormRequest
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
            'account_type_id' => 'required|integer|exists:account_types,id',
            'code' => [Rule::unique('accounts')->where(function ($query) {
                            return $query->where('owner_id', Auth::user()->id);
                        }), 'required'],
            'start_balance' => 'required|numeric',
            'date' => 'nullable|date',
            'description' => 'nullable|string'
        ];
    }
}

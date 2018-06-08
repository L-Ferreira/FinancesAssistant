<?php

namespace App\Http\Requests;

use App\Rules\GreaterThanRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateMovementRequest extends FormRequest
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
            'movement_category_id' => 'required|integer|exists:movement_categories,id',
            'value' => [new GreaterThanRule(), 'required','numeric'],
            'date' => 'required|date|before:now',
            'description' => 'nullable|string',
            'document_file' => 'file|required_with:document_description|mimes:jpg,png,pdf',
            'document_description' => 'nullable|string',
        ];
    }
}

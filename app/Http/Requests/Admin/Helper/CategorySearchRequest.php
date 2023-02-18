<?php

namespace App\Http\Requests\Admin\Helper;

use Illuminate\Foundation\Http\FormRequest;

class CategorySearchRequest extends FormRequest
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
            "restaurant_id" => "required|exists:restaurants,id"
        ];
    }
}

<?php

namespace App\Http\Requests\Admin\Allowance;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            "type" => "required",
            "name"  => "required|unique:allowances",
            "for_all"  => "required|boolean",
            "value"  => "required",
        ];
    }
}

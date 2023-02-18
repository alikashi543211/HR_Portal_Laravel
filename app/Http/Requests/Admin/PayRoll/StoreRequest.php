<?php

namespace App\Http\Requests\Admin\PayRoll;

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
            "date" => "required",
            "user_id"  => "required|array|min:1",
            "user_id.*"  => "exists:users,id",
        ];
    }
}

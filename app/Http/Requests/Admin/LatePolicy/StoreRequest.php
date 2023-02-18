<?php

namespace App\Http\Requests\Admin\LatePolicy;

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
            "name" => 'required|unique:late_policy,name|unique:late_policy_users,name',
            "start_time" => 'date_format:H:i|before:end_time|before:relax_time',
            "end_time" => 'date_format:H:i|after:start_time',
            "relax_time" => 'date_format:H:i|after:start_time',
        ];
    }
}

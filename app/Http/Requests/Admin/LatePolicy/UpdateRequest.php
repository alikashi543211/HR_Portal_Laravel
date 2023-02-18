<?php

namespace App\Http\Requests\Admin\LatePolicy;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            "start_time" => 'date_format:H:i|before:end_time|before:relax_time',
            "end_time" => 'date_format:H:i|after:start_time',
            "relax_time" => 'date_format:H:i|after:start_time',
        ];
    }
}

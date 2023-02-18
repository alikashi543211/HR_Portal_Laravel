<?php

namespace App\Http\Requests\Admin\Attendance;

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
            "type" => "required|in:" . CHECK_IN .','. CHECK_OUT,
            "user_id"  => "required|exists:users,id,role_id," . EMPLOYEE,
            "action_time"  => "required|date|date_format:Y-m-d H:i",
        ];
    }
}

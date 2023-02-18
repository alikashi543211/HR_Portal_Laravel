<?php

namespace App\Http\Requests\Api\Attendance;

use App\Http\Requests\Base\BaseRequest;

class StoreRequest extends BaseRequest
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
            'AttStatus' => 'required',
            'EnrollNo' => 'required|exists:users,finger_print_id',
        ];
    }
}

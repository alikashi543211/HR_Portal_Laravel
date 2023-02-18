<?php

namespace App\Http\Requests\Api\LeaveRequest;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateLeaveRequest extends BaseRequest
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
            'id' => 'required|exists:leave_requests,id',
            'type' => "required|in:" . SICK_LEAVE . "," . CASUAL_LEAVE,
            'period_type' => "required|in:" . FULL_DAY . "," . FIRST_HALF . ", " . SECOND_HALF,
            'from_date' => "required",
            'to_date' => "required",
            'reason' => "required"
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}

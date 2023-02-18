<?php

namespace App\Http\Requests\Api\LeaveRequest;

use App\Http\Requests\Base\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class NewLeaveRequest extends BaseRequest
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
            'type' => "required|int|in:" . SICK_LEAVE . "," . CASUAL_LEAVE,
            'period_type' => "required|int|in:" . FULL_DAY . "," . FIRST_HALF . ", " . SECOND_HALF,
            'from_date' => "required|date",
            'to_date' => "required|date",
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

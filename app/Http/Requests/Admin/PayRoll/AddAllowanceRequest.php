<?php

namespace App\Http\Requests\Admin\PayRoll;

use Illuminate\Foundation\Http\FormRequest;

class AddAllowanceRequest extends FormRequest
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
            "user_pay_roll_id"  => "exists:user_pay_rolls,id",
            "allowance_id"  => "exists:allowances,id",
        ];
    }
}

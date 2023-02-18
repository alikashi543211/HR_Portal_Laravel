<?php

namespace App\Http\Requests\Admin\User;

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
            "first_name" => "required",
            "last_name"  => "required",
            "gender"  => "required|in:Male,Female",
            "phone_number"  => "required",
            "cnic"  => "required",
            "designation"  => "required",
            "doj"  => "required|date",
            "dob"  => "required|date|before:" . date('Y-m-d', strtotime('- 10 years')),
            "dop"  => "nullable|date",
            "cnic"  => "required",
            "employee_id"  => "required",
            "finger_print_id"  => "nullable",
            "nationality"  => "required",
            "base_salary"  => "required",
            'manager_id' => 'nullable|exists:users,id',
            'policy_id' => 'nullable|exists:late_policy,id',
            "emergency_contact_name"  => "nullable",
            "emergency_contact_relation"  => "nullable",
            "emergency_contact_number"  => "nullable",
            "email"      => "email:rfc,dns|required|unique:users,email",
            "personal_email"      => "email:rfc,dns|required|unique:users,personal_email",
            "password"   => "required|min:8|confirmed:password_confirmation"
        ];
    }
}

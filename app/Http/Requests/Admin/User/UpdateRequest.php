<?php

namespace App\Http\Requests\Admin\User;

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
            'id'    => 'required|exists:users,id',
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
            // "finger_print_id"  => "required",
            "nationality"  => "required",
            "base_salary"  => "required",
            "emergency_contact_name"  => "nullable",
            "emergency_contact_relation"  => "nullable",
            "emergency_contact_number"  => "nullable",
            'email' => 'required|email:rfc,dns|unique:users,email,' . request('id'),
            "personal_email"      => "email:rfc,dns|required|unique:users,personal_email," . request('id'),
            "password"   => "nullable|min:8|confirmed:password_confirmation"
        ];
    }
}

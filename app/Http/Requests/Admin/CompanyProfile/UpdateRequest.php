<?php

namespace App\Http\Requests\Admin\CompanyProfile;

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
            "name"                     => "required",
            "phone"                    => "required",
            "address"                  => "required",
            // "logo"                     => "required",
            "authorized_name"          => "required",
            "authorized_designation"   => "required",
            "cheque_bank_name"         => "required_with:salary_details",
            "respective_bank_name"     => "required_with:salary_details",
            "respective_title"         => "required_with:salary_details",
            "respective_first_name"    => "required_with:salary_details",
            "respective_last_name"     => "required_with:salary_details",
            "respective_designation"   => "required_with:salary_details",
            "respective_address_1"     => "required_with:salary_details",
            "respective_address_2"     => "required_with:salary_details"
        ];
    }
}

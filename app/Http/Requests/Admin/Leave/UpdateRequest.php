<?php

namespace App\Http\Requests\Admin\Leave;

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
            "id" => 'required|array',
            "id.*" => 'exists:users,id',
            "remaining_casual_leaves" => 'required|array',
            "remaining_casual_leaves.*" => 'min:0',
            "remaining_sick_leaves" => 'required|array',
            "remaining_sick_leaves.*" => 'min:0',
        ];
    }
}

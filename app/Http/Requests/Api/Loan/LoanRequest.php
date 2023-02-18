<?php

namespace App\Http\Requests\Api\Loan;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Factory as ValidationFactory;

class LoanRequest extends FormRequest
{


    public function __construct(ValidationFactory $validationFactory)
    {
        $validationFactory->extend(
            'total_amount',
            function ($attribute, $value, $parameters) {
                $total = 0;
                foreach ($value as $d) {
                    if (isset($d['amount'])) {
                        $total += $d['amount'];
                    } else {
                        return "required";
                    }
                }
                return $total === request("totalAmount");
            },
            'Installments amount not equals to total amount'
        );
    }

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
            "totalAmount" => 'required',
            "installments" => "required|total_amount",
            "installments.*.month" => 'required|string|distinct|date_format:Y-m-d',
            "installments.*.amount" => 'required'
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

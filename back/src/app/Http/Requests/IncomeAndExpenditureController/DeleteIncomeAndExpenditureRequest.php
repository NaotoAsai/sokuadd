<?php

namespace App\Http\Requests\IncomeAndExpenditureController;

use App\Http\Requests\ApiRequest;
use App\Rules\IncomeAndExpenditureRules\ExistsIdRule;

class DeleteIncomeAndExpenditureRequest extends ApiRequest
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
            'id' => [
                'bail',
                'required',
                'integer',
                new ExistsIdRule
            ],
            'year' => [
                'bail',
                'required',
                'regex:/^(19[0-9]{2}|2[0-9]{3})$/'
            ],
            'month' => [
                'bail',
                'required',
                'regex:/^([1-9]|1[0-2])$/'
            ]
        ];
    }
}

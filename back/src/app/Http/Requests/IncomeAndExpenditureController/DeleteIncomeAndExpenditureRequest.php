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
            ]
        ];
    }
}

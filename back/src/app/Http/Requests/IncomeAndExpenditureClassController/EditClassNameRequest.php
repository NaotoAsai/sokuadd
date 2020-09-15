<?php

namespace App\Http\Requests\IncomeAndExpenditureClassController;

use App\Http\Requests\ApiRequest;
use App\Rules\IncomeAndExpenditureRules\ExistsClassIdRule;

class EditClassNameRequest extends ApiRequest
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
                new ExistsClassIdRule
            ],
            'name' => 'bail|required|max:32'
        ];
    }

    /**
     * 項目名
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => '分類名',
        ];
    }
}

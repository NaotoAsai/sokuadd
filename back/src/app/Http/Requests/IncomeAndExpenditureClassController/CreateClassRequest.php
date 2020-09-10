<?php

namespace App\Http\Requests\IncomeAndExpenditureClassController;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Requests\ApiRequest;

class CreateClassRequest extends ApiRequest
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
            'name' => 'required|max:16',
            'type' => 'required|regex:/^[01]$/'
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

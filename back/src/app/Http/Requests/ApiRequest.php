<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends FormRequest
{
    /**
     * API バリデーションエラー
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $res = response()->json([
            'status' => 422,
            'message' => '入力内容を確認して下さい。',
            'errors' => $validator->errors(),
        ], 422);
        throw new HttpResponseException($res);
    }
}

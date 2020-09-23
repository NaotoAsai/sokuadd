<?php

namespace App\Http\Requests\UserController;

use App\Http\Requests\ApiRequest;
use App\Rules\UserRules\MatchPasswordRule;

class EditPasswordRequest extends ApiRequest
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
            'password' => [
                'bail',
                'required',
                'string',
                'min:8',
                'max:255',
                new MatchPasswordRule
            ],
            'newPassword' => 'bail|required|string|min:8|max:255|confirmed'
        ];
    }
}

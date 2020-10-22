<?php

namespace App\Http\Requests\ResetPasswordController;

use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class ResetPasswordRequest extends ApiRequest
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
            'genericToken' => 'bail|required|string',
            'password' => 'bail|required|string|max:40|min:8'
        ];
    }
}

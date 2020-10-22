<?php

namespace App\Http\Requests\EmailController;

use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class PreEditEmailRequest extends ApiRequest
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
            'email' => [
                'bail',
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('users', 'email')
                // Rule::unique('users', 'email')->whereNull('deleted_at')
            ],
            'password' => 'required|string|min:8|max:255'
        ];
    }
}
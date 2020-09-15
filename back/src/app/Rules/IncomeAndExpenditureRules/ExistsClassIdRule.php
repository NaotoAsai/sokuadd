<?php

namespace App\Rules\IncomeAndExpenditureRules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\IncomeAndExpenditureClass;
use Illuminate\Support\Facades\Auth;

class ExistsClassIdRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 更新対象のidが当該ユーザーに紐づくレコードに存在するか
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return IncomeAndExpenditureClass::where('user_id', Auth::id())
            ->where('id', $value)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '分類が存在しません';
    }
}

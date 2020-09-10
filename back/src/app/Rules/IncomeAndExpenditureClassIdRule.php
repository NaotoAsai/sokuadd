<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\IncomeAndExpenditureClass;
use Illuminate\Support\Facades\Auth;

class IncomeAndExpenditureClassIdRule implements Rule
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
     * Determine if the validation rule passes.
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
        return '更新対象が不正です';
    }
}

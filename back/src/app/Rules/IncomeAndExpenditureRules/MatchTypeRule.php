<?php

namespace App\Rules\IncomeAndExpenditureRules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\IncomeAndExpenditureClass;

class MatchTypeRule implements Rule
{
    private $type;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(?int $type)
    {
        $this->type = $type;
    }

    /**
     * 新規作成する収支情報のtypeと、それに付与した分類のtypeが一致するか
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // typeがnullならここではバリデーションしない
        if ($this->type === null)
        {
            return true;
        }
        
        $class = IncomeAndExpenditureClass::where('id', $value)->first();
        return $class->type === $this->type;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '分類が不正です';
    }
}

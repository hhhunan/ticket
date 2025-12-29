<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pattern = '/^\+[1-9]\d{1,14}$/';

        if (!preg_match($pattern, $value)) {
            $fail('Телефон должен быть в международном формате E.164 (пример: +79991234567)');
        }
    }
}

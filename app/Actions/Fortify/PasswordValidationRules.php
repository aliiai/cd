<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     * 
     * قواعد كلمة المرور:
     * - 8 أحرف على الأقل
     * - حرف كبير على الأقل
     * - حرف صغير على الأقل
     * - رقم على الأقل
     * - علامة خاصة على الأقل
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return [
            'required', 
            'string', 
            Password::min(8)
                ->mixedCase()      // حرف كبير وصغير
                ->numbers()        // رقم على الأقل
                ->symbols(),       // علامة خاصة على الأقل
            'confirmed'
        ];
    }
}

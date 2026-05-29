<?php

namespace JamesMosquera\FiscalColombia\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use JamesMosquera\FiscalColombia\Support\NitValidator;

class NitRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!NitValidator::esValido((string) $value)) {
            $fail('El :attribute no es un NIT colombiano válido (verifique el dígito de verificación).');
        }
    }
}

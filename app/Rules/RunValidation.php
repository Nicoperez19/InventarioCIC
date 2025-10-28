<?php
namespace App\Rules;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
class RunValidation implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->isValidRun($value)) {
            $fail('El RUN debe tener el formato correcto: 12345678-9 (7-8 dígitos seguidos de guión y dígito verificador).');
        }
    }
    private function isValidRun(string $run): bool
    {
        $run = trim($run);
        if (empty($run)) {
            return false;
        }
        $pattern = '/^[0-9]{7,8}-[0-9kK]$/';
        return preg_match($pattern, $run);
    }
}
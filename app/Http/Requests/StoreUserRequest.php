<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // La autorización específica se maneja en el controlador
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('run')) {
            $this->merge([
                'run' => $this->normalizeRun($this->input('run')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'run' => ['required', 'string', 'max:255', 'regex:/^\\d{7,8}-[\\dK]$/', 'unique:users,run'],
            'nombre' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'email', 'max:255', 'unique:users,correo'],
            'contrasena' => ['required', 'string', 'min:8', 'confirmed'],
            'id_depto' => ['required', 'string', 'exists:departamentos,id_depto'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
    }

    private function normalizeRun(?string $run): string
    {
        $run = (string) $run;
        $clean = preg_replace('/[^0-9kK]/', '', $run) ?? '';
        if ($clean === '') {
            return '';
        }
        if (strlen($clean) === 1) {
            return strtoupper($clean);
        }
        $body = substr($clean, 0, -1);
        $dv = strtoupper(substr($clean, -1));

        return $body.'-'.$dv;
    }
}

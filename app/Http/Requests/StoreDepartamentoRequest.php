<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_depto' => ['required', 'string', 'max:255', 'unique:departamentos,id_depto'],
            'nombre_depto' => ['required', 'string', 'max:255'],
        ];
    }
}

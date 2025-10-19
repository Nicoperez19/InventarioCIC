<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnidadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_unidad' => ['required', 'string', 'max:255', 'unique:unidads,id_unidad'],
            'nombre_unidad' => ['required', 'string', 'max:255'],
        ];
    }
}

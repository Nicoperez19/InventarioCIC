<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('inventario'));
    }

    public function rules(): array
    {
        return [
            'cantidad_inventario' => ['required', 'integer', 'min:0'],
            'fecha_inventario' => ['required', 'date'],
        ];
    }
}

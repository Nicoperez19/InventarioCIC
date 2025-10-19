<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-inventory');
    }

    public function rules(): array
    {
        return [
            'id_inventario' => ['required', 'string', 'max:255', 'unique:inventarios,id_inventario'],
            'id_producto' => ['required', 'string', 'exists:productos,id_producto'],
            'fecha_inventario' => ['required', 'date'],
            'cantidad_inventario' => ['required', 'integer', 'min:0'],
        ];
    }
}

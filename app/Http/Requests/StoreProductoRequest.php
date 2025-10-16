<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create-products');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id_producto' => ['required', 'string', 'max:255', 'unique:productos,id_producto'],
            'nombre_producto' => ['required', 'string', 'max:255'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'stock_actual' => ['required', 'integer', 'min:0'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
            'id_unidad' => ['required', 'string', 'exists:unidads,id_unidad'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'id_producto.required' => 'El ID del producto es obligatorio.',
            'id_producto.unique' => 'El ID del producto ya existe.',
            'nombre_producto.required' => 'El nombre del producto es obligatorio.',
            'stock_minimo.required' => 'El stock mínimo es obligatorio.',
            'stock_minimo.min' => 'El stock mínimo debe ser mayor o igual a 0.',
            'stock_actual.required' => 'El stock actual es obligatorio.',
            'stock_actual.min' => 'El stock actual debe ser mayor o igual a 0.',
            'id_unidad.required' => 'La unidad es obligatoria.',
            'id_unidad.exists' => 'La unidad seleccionada no existe.',
        ];
    }
}

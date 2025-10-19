<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolicitudRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create-requests');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'observaciones' => ['nullable', 'string', 'max:1000'],
            'productos' => ['required', 'array', 'min:1'],
            'productos.*.id_producto' => ['required', 'string', 'exists:productos,id_producto'],
            'productos.*.cantidad' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'productos.required' => 'Debe seleccionar al menos un producto.',
            'productos.min' => 'Debe seleccionar al menos un producto.',
            'productos.*.id_producto.required' => 'El producto es obligatorio.',
            'productos.*.id_producto.exists' => 'El producto seleccionado no existe.',
            'productos.*.cantidad.required' => 'La cantidad es obligatoria.',
            'productos.*.cantidad.min' => 'La cantidad debe ser mayor a 0.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Verificar que todos los productos tengan stock suficiente
            foreach ($this->input('productos', []) as $index => $producto) {
                if (isset($producto['id_producto']) && isset($producto['cantidad'])) {
                    $productoModel = \App\Models\Producto::find($producto['id_producto']);
                    if ($productoModel && ! $productoModel->canReduceStock($producto['cantidad'])) {
                        $validator->errors()->add(
                            "productos.{$index}.cantidad",
                            "No hay suficiente stock para el producto: {$productoModel->nombre_producto}"
                        );
                    }
                }
            }
        });
    }
}

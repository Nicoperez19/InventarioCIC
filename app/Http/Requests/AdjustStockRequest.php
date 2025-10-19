<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdjustStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('producto'));
    }

    public function rules(): array
    {
        return [
            'cantidad' => ['required', 'integer'],
            'tipo_movimiento' => ['required', 'string', 'in:entrada,salida'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ];
    }
}

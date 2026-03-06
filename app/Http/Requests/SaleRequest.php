<?php

namespace App\Http\Requests;


class SaleRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sale_date' => 'required|date',
            'email' => 'required|email',
            'concepts' => 'required|array|min:1',
            'concepts.*.quantity' => 'required|numeric|min:1',
            'concepts.*.product_id' => 'required|exists:product,id',
        ];
    }
    public function messages()
    {
        return [
            'sale_date.required' => 'La fecha de venta es obligatoria.',
            'sale_date.date' => 'La fecha de venta debe ser una fecha válida.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'concepts.required' => 'Debe agregar al menos un concepto a la venta.',
            'concepts.array' => 'Los conceptos deben ser un arreglo.',
            'concepts.min' => 'Debe agregar al menos un concepto a la venta.',
            'concepts.*.quantity.required' => 'La cantidad es obligatoria para cada concepto.',
            'concepts.*.quantity.numeric' => 'La cantidad debe ser un número.',
            'concepts.*.quantity.min' => 'La cantidad debe ser al menos 1.',
            'concepts.*.product_id.required' => 'El ID del producto es obligatorio para cada concepto.',
            'concepts.*.product_id.exists' => 'El ID del producto debe existir en la tabla de productos.',
        ];
    }
}

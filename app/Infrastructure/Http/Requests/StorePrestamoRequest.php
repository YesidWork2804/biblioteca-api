<?php

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrestamoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'usuario_id' => ['required', 'integer', 'exists:usuarios,id'],
            'libro_id' => ['required', 'integer', 'exists:libros,id'],
            'fecha_prestamo' => ['required', 'date'],
            'fecha_devolucion_estimada' => ['required', 'date', 'after_or_equal:fecha_prestamo'],
        ];
    }

    public function messages(): array
    {
        return [
            'usuario_id.exists' => 'El usuario no existe',
            'libro_id.exists' => 'El libro no existe',
            'fecha_devolucion_estimada.after_or_equal' => 'La fecha estimada debe ser igual o posterior a la fecha de préstamo',
        ];
    }
}

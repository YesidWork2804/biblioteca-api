<?php

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:20', 'unique:libros,isbn'],
            'anio_publicacion' => ['required', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'numero_paginas' => ['required', 'integer', 'min:1'],
            'descripcion' => ['nullable', 'string'],
            'stock_disponible' => ['required', 'integer', 'min:0'],
            'autores' => ['required', 'array', 'min:1'],
            'autores.*' => ['exists:autores,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'El título es obligatorio',
            'isbn.unique' => 'El ISBN ya está registrado',
            'autores.required' => 'Debe asignar al menos un autor',
            'autores.*.exists' => 'Uno o más autores no existen',
        ];
    }
}

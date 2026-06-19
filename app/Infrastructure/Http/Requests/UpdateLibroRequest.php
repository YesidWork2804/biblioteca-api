<?php

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLibroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $libroId = $this->route('libro');

        return [
            'titulo' => ['sometimes', 'required', 'string', 'max:255'],
            'isbn' => ['sometimes', 'required', 'string', 'max:20', "unique:libros,isbn,{$libroId}"],
            'anio_publicacion' => ['sometimes', 'required', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'numero_paginas' => ['sometimes', 'required', 'integer', 'min:1'],
            'descripcion' => ['sometimes', 'nullable', 'string'],
            'stock_disponible' => ['sometimes', 'required', 'integer', 'min:0'],
            'autores' => ['sometimes', 'array', 'min:1'],
            'autores.*' => ['exists:autores,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'isbn.unique' => 'El ISBN ya está registrado en otro libro',
        ];
    }
}

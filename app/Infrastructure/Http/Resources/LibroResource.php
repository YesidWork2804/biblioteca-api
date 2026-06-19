<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LibroResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'isbn' => $this->isbn,
            'anio_publicacion' => $this->anio_publicacion,
            'numero_paginas' => $this->numero_paginas,
            'descripcion' => $this->descripcion,
            'stock_disponible' => $this->stock_disponible,
            'autores' => AutorResource::collection($this->whenLoaded('autores')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

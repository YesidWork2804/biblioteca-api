<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrestamoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fecha_prestamo' => $this->fecha_prestamo?->format('Y-m-d'),
            'fecha_devolucion_estimada' => $this->fecha_devolucion_estimada?->format('Y-m-d'),
            'fecha_devolucion_real' => $this->fecha_devolucion_real?->format('Y-m-d'),
            'estado' => $this->estado,
            'libro' => $this->whenLoaded('libro', function () {
                return [
                    'id' => $this->libro->id,
                    'titulo' => $this->libro->titulo,
                    'isbn' => $this->libro->isbn,
                ];
            }),
            'usuario' => $this->whenLoaded('usuario', function () {
                return [
                    'id' => $this->usuario->id,
                    'nombre_completo' => $this->usuario->nombre_completo,
                    'email' => $this->usuario->email,
                ];
            }),
        ];
    }
}

<?php

namespace App\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre_completo' => $this->nombre_completo,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'nacionalidad' => $this->nacionalidad,
        ];
    }
}

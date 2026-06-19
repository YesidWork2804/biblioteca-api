<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'fecha_registro',
        'estado',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
        'estado' => 'boolean',
    ];

    public function prestamos(): HasMany
    {
        return $this->hasMany(Prestamo::class);
    }

    public function prestamosActivos(): HasMany
    {
        return $this->hasMany(Prestamo::class)->where('estado', 'activo');
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }
}

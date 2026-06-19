<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Autor extends Model
{
    use HasFactory;

    protected $table = 'autores';

    protected $fillable = [
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'nacionalidad',
        'biografia',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function libros(): HasMany
    {
        return $this->hasMany(Libro::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }
}

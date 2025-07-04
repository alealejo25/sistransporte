<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcaCoche extends Model
{
    protected $table = 'marcas_coches';

    protected $fillable = [
        'descripcion',
        'activo',
    ];

    // Relación: una MarcaCoche tiene muchos Coches
    public function coches()
    {
        return $this->hasMany(Coche::class, 'marca_coche_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carroceria extends Model
{
    protected $table = 'carrocerias';

    protected $fillable = [
        'descripcion',
        'activo',
    ];

    public function coches()
    {
        return $this->hasMany(Coche::class, 'carroceria_id');
    }
}
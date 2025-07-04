<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marcas';
    protected $fillable = ['descripcion', 'activo'];

    public function repuestos()
    {
        return $this->hasMany(Repuesto::class);
    }
}

<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $table = 'modelos';

    protected $fillable = [
        'descripcion',
        'activo',
    ];

    public function coches()
    {
        return $this->hasMany(Coche::class, 'modelo_id');
    }
}
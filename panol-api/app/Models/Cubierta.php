<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cubierta extends Model
{
    protected $table = 'cubiertas';
    protected $fillable = [
        'numero_unico', 'empresa_id', 'repuesto_id', 'estado', 'activo'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class);
    }
}

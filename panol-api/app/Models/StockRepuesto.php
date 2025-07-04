<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRepuesto extends Model
{
    protected $table = 'stock_repuestos';
    protected $fillable = [
        'empresa_id', 'repuesto_id', 'cantidad', 'estado', 'fecha_actualiza', 'user_id', 'activo'
    ];

    public function empresa() {
        return $this->belongsTo(\App\Models\Empresa::class);
    }

    public function repuesto() {
        return $this->belongsTo(\App\Models\Repuesto::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}

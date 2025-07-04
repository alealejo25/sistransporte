<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repuesto extends Model
{
    protected $table = 'repuestos';
    protected $fillable = [
        'codigo', 'descripcion', 'marca_id', 'tipo', 'unidad_medida', 'stock_global', 'valor', 'activo'
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function stockRepuestos()
    {
        return $this->hasMany(StockRepuesto::class);
    }

    public function cubiertas()
    {
        return $this->hasMany(Cubierta::class);
    }

    public function comprobanteIngresoDetalles()
    {
        return $this->hasMany(ComprobanteIngresoDetalle::class, 'repuesto_id');
    }

    public function serviceDetalles()
    {
        return $this->hasMany(ServiceDetalle::class, 'repuesto_id');
    }

    public function transferenciasRepuestos()
    {
        return $this->hasMany(TransferenciaRepuesto::class, 'repuesto_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComprobanteIngresoDetalle extends Model
{
    protected $table = 'comprobante_ingreso_detalle';
    protected $fillable = [
        'comprobante_ingreso_id',
        'repuesto_id',
        'cantidad',
        'costo_unitario',
        'observacion'
    ];

    public function comprobante()
    {
        return $this->belongsTo(ComprobanteIngreso::class, 'comprobante_ingreso_id');
    }
    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class, 'repuesto_id');
    }
}

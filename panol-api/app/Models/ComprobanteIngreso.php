<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComprobanteIngreso extends Model
{
    protected $table = 'comprobante_ingreso';
    protected $fillable = [
        'tipo_comprobante_id',
        'numero',
        'fecha',
        'turno_panol_id',
        'proveedor_id',
        'empresa_id',
        'observaciones',
        'usuario_id',
        'anulado',
        'usuario_anulacion_id',
        'fecha_anulacion',
        'archivo'
    ];

    public function detalles()
    {
        return $this->hasMany(ComprobanteIngresoDetalle::class, 'comprobante_ingreso_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
    public function tipo_comprobante()
    {
        return $this->belongsTo(TipoComprobante::class, 'tipo_comprobante_id');
    }
}

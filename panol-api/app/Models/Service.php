<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'empresa_id', // <-- agregado
        'coche_id',
        'empleado_id',
        'tipo',
        'fecha_asignacion',
        'fecha_terminacion',
        'descripcion',
        'observacion',
        'estado',
        'user_id',
        'km',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function coche()
    {
        return $this->belongsTo(Coche::class, 'coche_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detalles()
    {
        return $this->hasMany(ServiceDetalle::class, 'service_id');
    }

    public function historiales()
    {
        return $this->hasMany(ServiceHistorial::class, 'service_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceHistorial extends Model
{
    protected $table = 'service_historial';

    protected $fillable = [
        'service_id',
        'empleado_id',
        'estado_anterior',
        'estado_nuevo',
        'fecha_cambio',
        'observacion',
        'user_id',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceDetalle extends Model
{
    protected $fillable = [
        'service_id',
        'repuesto_id',
        'cantidad',
        'observaciones',
        'fecha_colocacion',
        'user_id',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class, 'repuesto_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

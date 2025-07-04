<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = [
        'id','nombre', 'apellido', 'dni', 'legajo', 'telefono', 'email', 'foto',
        'activo', 'user_id', 'empresa_id', 'puesto_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function empresa() {
        return $this->belongsTo(Empresa::class);
    }

    public function puesto() {
        return $this->belongsTo(Puesto::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'empleado_id');
    }

    public function serviceHistoriales()
    {
        return $this->hasMany(ServiceHistorial::class, 'empleado_id');
    }
}

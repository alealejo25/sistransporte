<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['id','descripcion','cuit','activo'];

    public function empleados() {
        return $this->hasMany(Empleado::class);
    }
    public function coches()
    {
        return $this->hasMany(Coche::class, 'empresa_id');
    }
    public function transferenciasOrigen()
    {
        return $this->hasMany(TransferenciaRepuesto::class, 'empresa_origen_id');
    }
    public function transferenciasDestino()
    {
        return $this->hasMany(TransferenciaRepuesto::class, 'empresa_destino_id');
    }
}
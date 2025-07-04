<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coche extends Model
{
    protected $table = 'coches';

    protected $fillable = [
        'interno',
        'qr_code',
        'nroempresa',
        'patente',
        'activo',
        'fechavtv',
        'vencimientovtv',
        'anio',
        'motor',
        'chasis',
        'nroasientos',
        'km',
        'ultimoservice',
        'fecha_ingreso',
        'fecha_egreso',
        'valor',
        'foto',
        'condicion',
        'carroceria_id',
        'modelo_id',
        'marca_coche_id',
        'empresa_id',
    ];

    public function carroceria()
    {
        return $this->belongsTo(Carroceria::class, 'carroceria_id');
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'modelo_id');
    }

    public function marcaCoche()
    {
        return $this->belongsTo(MarcaCoche::class, 'marca_coche_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'coche_id');
    }
}
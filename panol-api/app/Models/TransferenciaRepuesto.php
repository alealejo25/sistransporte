<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferenciaRepuesto extends Model
{
    use HasFactory;

    protected $table = 'transferencia_repuestos';

    protected $fillable = [
        'repuesto_id',
        'empresa_origen_id',
        'empresa_destino_id',
        'cantidad',
        'user_id',
    ];

  
    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class);
    }

    public function empresaOrigen()
    {
        return $this->belongsTo(Empresa::class, 'empresa_origen_id');
    }

    public function empresaDestino()
    {
        return $this->belongsTo(Empresa::class, 'empresa_destino_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

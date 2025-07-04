<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoStockRepuesto extends Model
{
    use HasFactory;

    protected $table = 'movimientos_stock_repuestos'; 

    protected $fillable = [
        'empresa_id',
        'repuesto_id',
        'cantidad',
        'observacion',
        'user_id',
        'fecha',
    ];
}

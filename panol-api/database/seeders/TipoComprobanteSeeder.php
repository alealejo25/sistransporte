<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos = ['FACTURA', 'RECIBO', 'REMITO', 'OTROS'];
        foreach ($tipos as $tipo) {
            DB::table('tipo_comprobante')->updateOrInsert(['descripcion' => $tipo]);
        }
    }
}

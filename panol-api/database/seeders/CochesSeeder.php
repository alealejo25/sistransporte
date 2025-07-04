<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CochesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coches = [
            [
                'qr_code' => Str::random(10),
                'interno' => 101,
                'nroempresa' => 1,
                'patente' => 'AA123AA',
                'activo' => true,
                'fechavtv' => '2025-01-10',
                'vencimientovtv' => '2026-01-10',
                'anio' => 2020,
                'motor' => 'MTR12345',
                'chasis' => 'CHS12345',
                'nroasientos' => 45,
                'km' => 120000,
                'ultimoservice' => '2025-06-01',
                'fecha_ingreso' => '2020-02-01',
                'fecha_egreso' => null,
                'valor' => 5000000,
                'foto' => null,
                'condicion' => 1,
                'carroceria_id' => 1,
                'modelo_id' => 1,
                'marca_coche_id' => 1,
                'empresa_id' => 1,
            ],
            
        ];

        
        foreach ($coches as $coche) {
            DB::table('coches')->insert($coche);
        }
    }
}

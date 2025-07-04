<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PuestosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $puestos = [
            ['descripcion' => 'Mecánico', 'activo' => true],
            ['descripcion' => 'Lavador', 'activo' => true],
        ];

        foreach ($puestos as $puesto) {
            DB::table('puestos')->updateOrInsert(['descripcion' => $puesto['descripcion']], $puesto);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarcasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marcas = [
            ['descripcion' => 'MACHIO BULCA', 'activo' => true],
            ['descripcion' => 'VARTA', 'activo' => true],
            ['descripcion' => 'SUBIRS', 'activo' => true],
            ['descripcion' => 'REPUESTOLANDIA', 'activo' => true],
        ];

        foreach ($marcas as $marca) {
            DB::table('marcas')->updateOrInsert(['descripcion' => $marca['descripcion']], $marca);
        }
    }
}

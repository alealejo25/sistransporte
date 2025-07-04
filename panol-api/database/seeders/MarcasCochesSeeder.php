<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarcasCochesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marcas = [
            ['descripcion' => 'Mercedes-Benz', 'activo' => true],
            ['descripcion' => 'Scania', 'activo' => true],
            ['descripcion' => 'Volvo', 'activo' => true],
            ['descripcion' => 'Iveco', 'activo' => true],
            ['descripcion' => 'Agrale', 'activo' => true],
            ['descripcion' => 'Volkswagen', 'activo' => true],
            ['descripcion' => 'MAN', 'activo' => true],
            ['descripcion' => 'Renault Trucks', 'activo' => true],
            ['descripcion' => 'Ford', 'activo' => true],
            ['descripcion' => 'Hino', 'activo' => true],
        ];

        foreach ($marcas as $marca) {
            DB::table('marcas_coches')->updateOrInsert(['descripcion' => $marca['descripcion']], $marca);
        }
    }
}

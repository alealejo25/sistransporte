<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modelos = [
            ['descripcion' => 'EcoSport', 'activo' => true],
            ['descripcion' => 'Hilux', 'activo' => true],
            ['descripcion' => 'Kangoo', 'activo' => true],
            ['descripcion' => 'Partner', 'activo' => true],
        ];

        foreach ($modelos as $modelo) {
            DB::table('modelos')->updateOrInsert(['descripcion' => $modelo['descripcion']], $modelo);
        }
    }
}

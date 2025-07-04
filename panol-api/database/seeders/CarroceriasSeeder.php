<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarroceriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carrocerias = [
            ['descripcion' => 'Pick Up', 'activo' => true],
            ['descripcion' => 'Furgón', 'activo' => true],
            ['descripcion' => 'Sedán', 'activo' => true],
            ['descripcion' => 'SUV', 'activo' => true],
        ];

        foreach ($carrocerias as $carroceria) {
            DB::table('carrocerias')->updateOrInsert(['descripcion' => $carroceria['descripcion']], $carroceria);
        }
    }
}

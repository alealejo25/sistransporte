<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('empresas')->updateOrInsert(
            ['descripcion' => 'MALEBO'],
            [
                'cuit' => '30712345678',
                'activo' => true,
            ]
        );
    }
}

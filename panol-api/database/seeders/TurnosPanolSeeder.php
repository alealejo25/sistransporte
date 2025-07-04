<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TurnosPanolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $turnos = ['MAÑANA', 'TARDE', 'NOCHE'];
        foreach ($turnos as $turno) {
            DB::table('turnos_panol')->updateOrInsert(['descripcion' => $turno]);
        }
    }
}

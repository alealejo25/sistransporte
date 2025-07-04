<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Repuesto;
use App\Models\Empresa;

class StockRepuestosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $repuestos = Repuesto::all();
        $empresas = Empresa::all();

        foreach ($repuestos as $repuesto) {
            foreach ($empresas as $empresa) {
                DB::table('stock_repuestos')->updateOrInsert(
                    [
                        'empresa_id' => $empresa->id,
                        'repuesto_id' => $repuesto->id,
                    ],
                    [
                        'cantidad' => 0,
                        'estado' => 'nuevo',
                        'fecha_actualiza' => now(),
                        'user_id' => 1,
                        'activo' => true,
                    ]
                );
            }
        }
    }
}

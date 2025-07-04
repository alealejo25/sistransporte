<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            RolesTableSeeder::class,
            TurnosPanolSeeder::class,
            TipoComprobanteSeeder::class,
            ProveedoresSeeder::class,
            ModelosSeeder::class,
            MarcasSeeder::class,
            CarroceriasSeeder::class,
            EmpresasSeeder::class,
            PuestosSeeder::class,
            MarcasCochesSeeder::class,
            CochesSeeder::class,
            RepuestosSeeder::class,
            StockRepuestosSeeder::class,
            EmpleadosSeeder::class,
        ]);
    }
}

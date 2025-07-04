<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RepuestosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $repuestos = [
            ['codigo' => 'R001', 'descripcion' => 'Filtro de aire', 'valor' => 1200, 'marca_id' => 1, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R002', 'descripcion' => 'Filtro de aceite', 'valor' => 900, 'marca_id' => 1, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R003', 'descripcion' => 'Filtro de combustible', 'valor' => 1100, 'marca_id' => 2, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R004', 'descripcion' => 'Aceite de motor', 'valor' => 3500, 'marca_id' => 2, 'tipo' => 'General', 'unidad_medida' => 'Litro', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R005', 'descripcion' => 'Bujía', 'valor' => 500, 'marca_id' => 3, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R006', 'descripcion' => 'Correa de distribución', 'valor' => 2500, 'marca_id' => 3, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R007', 'descripcion' => 'Pastillas de freno', 'valor' => 1800, 'marca_id' => 4, 'tipo' => 'General', 'unidad_medida' => 'Par', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R008', 'descripcion' => 'Disco de freno', 'valor' => 2200, 'marca_id' => 4, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R009', 'descripcion' => 'Amortiguador', 'valor' => 3200, 'marca_id' => 1, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R010', 'descripcion' => 'Radiador', 'valor' => 4500, 'marca_id' => 2, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R011', 'descripcion' => 'Batería', 'valor' => 6000, 'marca_id' => 3, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R012', 'descripcion' => 'Cubierta 295/80R22.5', 'valor' => 95000, 'marca_id' => 4, 'tipo' => 'Cubiertas', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R013', 'descripcion' => 'Espejo retrovisor', 'valor' => 800, 'marca_id' => 1, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R014', 'descripcion' => 'Lámpara H4', 'valor' => 300, 'marca_id' => 2, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R015', 'descripcion' => 'Parabrisas', 'valor' => 7000, 'marca_id' => 3, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R016', 'descripcion' => 'Limpiaparabrisas', 'valor' => 400, 'marca_id' => 4, 'tipo' => 'General', 'unidad_medida' => 'Par', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R017', 'descripcion' => 'Tapa de combustible', 'valor' => 650, 'marca_id' => 1, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R018', 'descripcion' => 'Sensor de temperatura', 'valor' => 1200, 'marca_id' => 2, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R019', 'descripcion' => 'Termostato', 'valor' => 900, 'marca_id' => 3, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
            ['codigo' => 'R020', 'descripcion' => 'Turbocompresor', 'valor' => 18000, 'marca_id' => 4, 'tipo' => 'General', 'unidad_medida' => 'Unidad', 'stock_global' => 0, 'activo' => true],
        ];

        foreach ($repuestos as $rep) {
            DB::table('repuestos')->updateOrInsert(['codigo' => $rep['codigo']], $rep);
        }
    }
}

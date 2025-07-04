<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $proveedores = [
            [
                'nombre' => 'ROCHES',
                'cuit' => '20123456780',
                'email' => 'roches@proveedor.com',
                'telefono' => '1122334455',
                'direccion' => 'Calle 1 123',
                'activo' => true,
            ],
            [
                'nombre' => 'MIGUEL ARMANDO',
                'cuit' => '20987654321',
                'email' => 'miguel@proveedor.com',
                'telefono' => '2233445566',
                'direccion' => 'Calle 2 456',
                'activo' => true,
            ],
            [
                'nombre' => 'REPUES MCAN',
                'cuit' => '20345678901',
                'email' => 'mcaan@proveedor.com',
                'telefono' => '3344556677',
                'direccion' => 'Calle 3 789',
                'activo' => true,
            ],
            [
                'nombre' => 'REPUESTOS DEL SUR',
                'cuit' => '20456789012',
                'email' => 'delsur@proveedor.com',
                'telefono' => '4455667788',
                'direccion' => 'Calle 4 101',
                'activo' => true,
            ],
        ];

        foreach ($proveedores as $prov) {
            DB::table('proveedores')->updateOrInsert(['cuit' => $prov['cuit']], $prov);
        }
    }
}

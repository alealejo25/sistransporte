<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpleadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empleados = [
            ['nombre' => 'Juan', 'apellido' => 'Pérez', 'dni' => '30123456', 'legajo' => '1001', 'telefono' => '1122334455', 'email' => 'juan.perez@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 1, 'empresa_id' => 1],
            ['nombre' => 'María', 'apellido' => 'Gómez', 'dni' => '30234567', 'legajo' => '1002', 'telefono' => '1122334456', 'email' => 'maria.gomez@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 2, 'empresa_id' => 1],
            ['nombre' => 'Carlos', 'apellido' => 'López', 'dni' => '30345678', 'legajo' => '1003', 'telefono' => '1122334457', 'email' => 'carlos.lopez@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 1, 'empresa_id' => 1],
            ['nombre' => 'Ana', 'apellido' => 'Martínez', 'dni' => '30456789', 'legajo' => '1004', 'telefono' => '1122334458', 'email' => 'ana.martinez@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 2, 'empresa_id' => 1],
            ['nombre' => 'Pedro', 'apellido' => 'Sánchez', 'dni' => '30567890', 'legajo' => '1005', 'telefono' => '1122334459', 'email' => 'pedro.sanchez@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 1, 'empresa_id' => 1],
            ['nombre' => 'Lucía', 'apellido' => 'Fernández', 'dni' => '30678901', 'legajo' => '1006', 'telefono' => '1122334460', 'email' => 'lucia.fernandez@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 2, 'empresa_id' => 1],
            ['nombre' => 'Diego', 'apellido' => 'Ruiz', 'dni' => '30789012', 'legajo' => '1007', 'telefono' => '1122334461', 'email' => 'diego.ruiz@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 1, 'empresa_id' => 1],
            ['nombre' => 'Sofía', 'apellido' => 'Torres', 'dni' => '30890123', 'legajo' => '1008', 'telefono' => '1122334462', 'email' => 'sofia.torres@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 2, 'empresa_id' => 1],
            ['nombre' => 'Martín', 'apellido' => 'Ramírez', 'dni' => '30901234', 'legajo' => '1009', 'telefono' => '1122334463', 'email' => 'martin.ramirez@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 1, 'empresa_id' => 1],
            ['nombre' => 'Valentina', 'apellido' => 'Flores', 'dni' => '31012345', 'legajo' => '1010', 'telefono' => '1122334464', 'email' => 'valentina.flores@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 2, 'empresa_id' => 1],
            ['nombre' => 'Javier', 'apellido' => 'Acosta', 'dni' => '31123456', 'legajo' => '1011', 'telefono' => '1122334465', 'email' => 'javier.acosta@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 1, 'empresa_id' => 1],
            ['nombre' => 'Camila', 'apellido' => 'Silva', 'dni' => '31234567', 'legajo' => '1012', 'telefono' => '1122334466', 'email' => 'camila.silva@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 2, 'empresa_id' => 1],
            ['nombre' => 'Matías', 'apellido' => 'Castro', 'dni' => '31345678', 'legajo' => '1013', 'telefono' => '1122334467', 'email' => 'matias.castro@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 1, 'empresa_id' => 1],
            ['nombre' => 'Florencia', 'apellido' => 'Ortiz', 'dni' => '31456789', 'legajo' => '1014', 'telefono' => '1122334468', 'email' => 'florencia.ortiz@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 2, 'empresa_id' => 1],
            ['nombre' => 'Nicolás', 'apellido' => 'Molina', 'dni' => '31567890', 'legajo' => '1015', 'telefono' => '1122334469', 'email' => 'nicolas.molina@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 1, 'empresa_id' => 1],
            ['nombre' => 'Julieta', 'apellido' => 'Medina', 'dni' => '31678901', 'legajo' => '1016', 'telefono' => '1122334470', 'email' => 'julieta.medina@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 2, 'empresa_id' => 1],
            ['nombre' => 'Franco', 'apellido' => 'Herrera', 'dni' => '31789012', 'legajo' => '1017', 'telefono' => '1122334471', 'email' => 'franco.herrera@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 1, 'empresa_id' => 1],
            ['nombre' => 'Agustina', 'apellido' => 'Rojas', 'dni' => '31890123', 'legajo' => '1018', 'telefono' => '1122334472', 'email' => 'agustina.rojas@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 2, 'empresa_id' => 1],
            ['nombre' => 'Facundo', 'apellido' => 'Vega', 'dni' => '31901234', 'legajo' => '1019', 'telefono' => '1122334473', 'email' => 'facundo.vega@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 1, 'empresa_id' => 1],
            ['nombre' => 'Milagros', 'apellido' => 'Cabrera', 'dni' => '32012345', 'legajo' => '1020', 'telefono' => '1122334474', 'email' => 'milagros.cabrera@mail.com', 'foto' => null, 'activo' => true, 'user_id' => null, 'puesto_id' => 2, 'empresa_id' => 1],
        ];

        foreach ($empleados as $empleado) {
            DB::table('empleados')->updateOrInsert(
                ['dni' => $empleado['dni']],
                $empleado
            );
        }
    }
}

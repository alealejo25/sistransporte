<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('apellido');
            $table->string('dni')->unique();
            $table->string('legajo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('foto')->nullable(); // Ruta o nombre de imagen
            $table->boolean('activo')->default(true);

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('puesto_id')->nullable()->constrained('puestos')->nullOnDelete();
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->nullOnDelete();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
    {
        Schema::dropIfExists('empleados');
    }
}

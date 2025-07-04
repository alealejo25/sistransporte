<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('coche_id')->constrained('coches');
            $table->foreignId('empleado_id')->constrained('empleados');
            $table->date('fecha_asignacion');
            $table->date('fecha_terminacion')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('observacion')->nullable();
            $table->enum('estado', ['pendiente', 'en proceso', 'finalizado', 'cancelado'])->default('pendiente');
            $table->enum('tipo', ['servicio', 'reparacion'])->default('servicio'); // <-- AGREGADO
            $table->foreignId('user_id')->constrained('users');
            $table->unsignedInteger('km')->nullable();
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
        Schema::dropIfExists('services');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceHistorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_historial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('empleado_id')->constrained('empleados');
            $table->enum('estado_anterior', ['creado','pendiente', 'en proceso', 'finalizado', 'cancelado']);
            $table->enum('estado_nuevo', ['creado','pendiente', 'en proceso', 'finalizado', 'cancelado']);
            $table->timestamp('fecha_cambio');
            $table->foreignId('user_id')->constrained('users');
            $table->text('observacion')->nullable(); // <-- agregado
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
        Schema::dropIfExists('service_historial');
    }
}


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferenciaRepuestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferencia_repuestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repuesto_id')->constrained('repuestos');
            $table->foreignId('empresa_origen_id')->constrained('empresas');
            $table->foreignId('empresa_destino_id')->constrained('empresas');
            $table->integer('cantidad');
            $table->foreignId('user_id')->constrained('users');
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
        Schema::dropIfExists('transferencia_repuestos');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobante_ingreso', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_comprobante_id');
            $table->string('numero');
            $table->date('fecha');
            $table->unsignedBigInteger('turno_panol_id');
            $table->unsignedBigInteger('proveedor_id')->nullable();
            $table->unsignedBigInteger('empresa_id');
            $table->text('observaciones')->nullable();
            $table->string('archivo')->nullable(); // <-- Campo para el archivo adjunto
            $table->unsignedBigInteger('usuario_id');
            $table->boolean('anulado')->default(false);
            $table->unsignedBigInteger('usuario_anulacion_id')->nullable();
            $table->timestamp('fecha_anulacion')->nullable();
            $table->timestamps();

            $table->foreign('tipo_comprobante_id')->references('id')->on('tipo_comprobante');
            $table->foreign('turno_panol_id')->references('id')->on('turnos_panol');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('usuario_anulacion_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comprobante_ingreso');
    }
};

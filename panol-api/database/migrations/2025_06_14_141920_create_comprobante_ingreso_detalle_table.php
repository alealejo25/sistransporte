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
        Schema::create('comprobante_ingreso_detalle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comprobante_ingreso_id');
            $table->unsignedBigInteger('repuesto_id');
            $table->decimal('cantidad', 10, 2);
            $table->decimal('costo_unitario', 12, 2)->nullable();
            $table->string('observacion')->nullable();
            $table->timestamps();
            $table->foreign('comprobante_ingreso_id')->references('id')->on('comprobante_ingreso');
            $table->foreign('repuesto_id')->references('id')->on('repuestos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comprobante_ingreso_detalle');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCochesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coches', function (Blueprint $table) {
            $table->id();
            $table->text('qr_code')->nullable(); // <-- Campo para el QR
            $table->integer('interno');
            $table->integer('nroempresa');
            $table->string('patente', 10);
            $table->boolean('activo')->default(true);
            $table->date('fechavtv')->nullable();
            $table->date('vencimientovtv')->nullable();
            $table->integer('anio');
            $table->string('motor', 30);
            $table->string('chasis', 30);
            $table->integer('nroasientos');
            $table->integer('km')->nullable();
            $table->date('ultimoservice')->nullable();
            $table->date('fecha_ingreso');
            $table->date('fecha_egreso')->nullable();
            $table->integer('valor');
            $table->string('foto', 256)->nullable();
            $table->integer('condicion')->unsigned()->default(0);

            $table->foreignId('carroceria_id')->nullable()->constrained('carrocerias');
            $table->foreignId('modelo_id')->nullable()->constrained('modelos');
            $table->foreignId('marca_coche_id')->nullable()->constrained('marcas_coches');
            $table->foreignId('empresa_id')->nullable()->constrained('empresas');

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
        Schema::dropIfExists('coches');
    }
}

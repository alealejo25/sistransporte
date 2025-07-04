<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCubiertasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
Schema::create('cubiertas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_unico')->unique();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('repuesto_id')->constrained('repuestos');
            $table->enum('estado', ['nueva', 'usada', 'reparada', 'baja'])->default('nueva');
            $table->boolean('activo')->default(true);
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
        Schema::dropIfExists('cubiertas');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarcasCochesTable extends Migration
{
    public function up()
    {
        Schema::create('marcas_coches', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('marcas_coches');
    }
}
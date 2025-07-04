<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepuestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repuestos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('descripcion');
            $table->decimal('valor', 12, 2)->default(0);
            $table->foreignId('marca_id')->constrained('marcas');
            $table->enum('tipo', ['General', 'Cubiertas']);
            $table->enum('unidad_medida', ['Unidad', 'Litro', 'Par']);
            $table->integer('stock_global')->default(0);
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
        Schema::dropIfExists('repuestos');
    }
}

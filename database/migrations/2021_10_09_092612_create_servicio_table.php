<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',64);
            $table->string('apellidos',64);
            $table->string('edad',32);
            $table->string('celular',32);
            $table->integer('area');
            $table->text('experiencia');
            $table->boolean('mi_renuevo');
            $table->string('asistiendo',32);
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
        Schema::dropIfExists('servicio');
    }
}

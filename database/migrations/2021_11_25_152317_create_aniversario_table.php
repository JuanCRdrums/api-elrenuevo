<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAniversarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aniversario', function (Blueprint $table) {
            $table->id();
            $table->string('cedula');
            $table->tinyInteger('servicio');
            $table->boolean('asistencia');
            $table->boolean('activo');
            $table->boolean('nino');
            $table->boolean('nuevo');
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
        Schema::dropIfExists('aniversario');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Inscripcion;

class CreateInscripcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Inscripcion::DB_TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('cedula');
            $table->tinyInteger('servicio');
            $table->boolean('asistencia');
            $table->boolean('activo');
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
        Schema::dropIfExists(Inscripcion::DB_TABLE);
    }
}

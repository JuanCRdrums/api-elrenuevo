<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;
    const DB_TABLE = 'inscripciones';
    protected $table = self::DB_TABLE;

    protected $fillable = ['id','cedula','servicio','asistencia','activo','created_at','updated_at','nino','fecha'];

    public function infoasistente() {
        return $this->hasOne('App\Models\Asistente', 'cedula', 'cedula');
    }

}

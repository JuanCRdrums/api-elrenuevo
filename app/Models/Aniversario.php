<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aniversario extends Model
{
    use HasFactory;
    const DB_TABLE = 'aniversario';
    protected $table = self::DB_TABLE;

    protected $fillable = ['id','cedula','servicio','asistencia','activo','created_at','updated_at','nino','nuevo'];

    public function infoasistente() {
        return $this->hasOne('App\Models\Asistente', 'cedula', 'cedula');
    }
}

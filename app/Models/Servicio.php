<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    const DB_TABLE = 'servicio';
    protected $table = self::DB_TABLE;

    protected $fillable = ['id','nombre','apellidos','edad','celular', 'area', 'experiencia','mi_renuevo',
    'asistiendo','created_at','updated_at'];

    public function NombreArea(){
        return config('options.areas_servicio')[$this->area];
    }

}

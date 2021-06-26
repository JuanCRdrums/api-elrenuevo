<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opciones extends Model
{
    use HasFactory;

    const DB_TABLE = 'opciones';
    protected $table = self::DB_TABLE;

    protected $fillable = ['id','clave','valor'];

    public static function getOptionValue($clave){
        $opciones = Self::all();
        foreach($opciones as $opcion)
        {
            if($opcion->clave == $clave)
                return $opcion->valor;
        }
        return 0;
    }

    public static function setOptionValue($clave, $valor){
        $data = [
            'clave' => $clave,
            'valor' => $valor,
        ];
        $opciones = Self::all();
        foreach($opciones as $opcion)
        {
            if($opcion->clave == $data['clave'])
            {
                $opcion->valor = $data['valor'];
                $opcion->save();
            }

        }
        if(count($opciones) == 0){
            $opcion = self::create($data);
        }
        return 1;
    }
}

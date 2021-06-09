<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistente extends Model
{
    use HasFactory;

    const DB_TABLE = 'asistentes';
    protected $table = self::DB_TABLE;

    protected $fillable = ['id','cedula','nombre','nacimiento','telefono','email','habilitado','created_at','updated_at'];
}

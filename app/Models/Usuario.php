<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Hash;


class Usuario extends Model implements AuthenticatableContract
{
    use Authenticatable;

    const DB_TABLE = 'usuarios';
    protected $table = self::DB_TABLE;

    protected $fillable = ['id','login','password','api_key','created_at','updated_at'];


    public function setPasswordAttribute($value) {
        if (!empty($value)) {
            if (Hash::needsRehash($value)) {
                $value = Hash::make($value);
            }
            $this->attributes['password'] = $value;
        }
    }


}

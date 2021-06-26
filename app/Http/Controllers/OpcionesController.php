<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Opciones;

class OpcionesController extends Controller
{
    public function opcion($opcion){
        return Opciones::getOptionValue($opcion);
    }

    public function setOption(Request $request){
        $data = $request->all();
        return Opciones::setOptionValue($data['opcion'],$data['valor']);
    }
}

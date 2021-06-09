<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\Asistente;

class InscripcionesController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $data['asistencia'] = 0;
        $data['activo'] = 0;
        $data['habilitado'] = 1;

        Inscripcion::create($data);
        Asistente::create($data);


        return $data;
    }
}

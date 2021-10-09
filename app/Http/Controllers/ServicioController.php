<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Exception;

class ServicioController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            Servicio::create($data);
            $msg = $data['nombre'] . ", has quedado registrado en nuestra convocatoria de servicio. 
            Pronto nos contactaremos contigo.";
            return [
                "error" => 0,
                "msg" => $msg,
            ];
        } catch(Exception $ex) {
            return [
                "error" => 1,
                "msg" => "No hemos podido guardar tu inscripción. Por favor inténtalo nuevamente"
            ];
        }
        
    }

    public function index()
    {
        $inscripciones = servicio::all();
        foreach($inscripciones as $inscripcion)
            $inscripcion->nombreArea = $inscripcion->NombreArea();
        return $inscripciones;
    }
}

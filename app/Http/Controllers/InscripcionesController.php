<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\Asistente;
use Carbon\Carbon;

class InscripcionesController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $data['asistencia'] = 0;
        $data['activo'] = 1;
        $data['habilitado'] = 1;
        $data['fecha'] = Carbon::create(2022,11,6,12)->toDateString();
        $data['email'] = "null@null.com"; //temporal
        //valores por defecto (solo para Santiago Benavides)
        $data['servicio'] = 1;
        $data['nuevo'] = 0;
        $data['nacimiento'] = Carbon::create("today")->toDateString();



        //VALIDAR QUE EL USUARIO NO TENGA UNA INSCRIPCIÓN ACTIVA PARA EL MISMO DÍA
        $existente = Inscripcion::where('cedula','=',$data['cedula'])->where('fecha','=',$data['fecha'])->where('activo','=','1')->get();
        if(count($existente))
        {
            return [
                "error" => 1,
                "msg" => 'Ya tienes una inscripción registrada',
            ];
        }



        //VALIDAR CANTIDAD DE ASISTENTES
        //Niños
        /*if($data['nino'] == 1){
            $inscritos = Inscripcion::where('nino', '=', 1)->where('fecha','=',$data['fecha'])->where('activo','=',1)->where('servicio','=',$data['servicio'])->get();
            if(count($inscritos) >= 20)
            return [
                "error" => 1,
                "msg" => 'El aforo de niños para este horario ya se ha completado. Te recomendamos que selecciones un horario diferente o que te quedes en casa y te conectes
                a la iglesia en línea a través de nuestro canal de Youtube.',
            ];
        }*/


        //Adultos
        if($data['nino'] == 0){
            $inscritos = Inscripcion::where('nino', '=', 0)->where('fecha','=',$data['fecha'])->where('activo','=',1)->get();
            if(count($inscritos) >= 400)
            return [
                "error" => 1,
                "msg" => 'El aforo de adultos para este horario ya se ha completado.',
            ];
        }


        $dataUsuario = [
            'cedula' => $data['cedula'],
            'nombre' => $data['nombre'],
            'nacimiento' => $data['nacimiento'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'habilitado' => $data['habilitado']
        ];

        Inscripcion::create($data);
        Asistente::updateOrCreate( ['cedula' => $data['cedula']], $dataUsuario);


        return [
            "error" => 0,
            "msg" => 'Registro correcto',
        ];
    }



    public function datosBasicos(Request $request)
    {
        $data = $request->all();
        $usuario = Asistente::where('cedula', '=', $data['cedula'])->get();
        if(count($usuario) == 1)
            return $usuario;
        else
            return [];
    }

    public function consultar(Request $request)
    {
        $data = $request->all();
        $fecha = Carbon::create(2022,11,6,12)->toDateString();
        $inscripcion = Inscripcion::where('cedula','=',$data['cedula'])->where('fecha', '=', $fecha)->where('activo','=',1)->get();
        if(count($inscripcion))
        {
            $inscripcion[0]->infoasistente;
            return $inscripcion;
        }
        else
            return [];
    }


    public function cancelar(Request $request)
    {
        $data = $request->all();
        $inscripcion = Inscripcion::find($data['id']);
        $inscripcion->activo = 0;
        $inscripcion->save();
        return 1;
    }


    public function fechaActiva()
    {
        //$fecha = Carbon::parse('this sunday');
        $fecha = Carbon::create(2022,11,6,12);
        $parsed = date_format($fecha, "d/m/Y");
        return $parsed;
    }



    public function inscripciones(Request $request)
    {
        $fecha = Carbon::create(2022,11,6,12)->toDateString();
        $consulta = Inscripcion::where('fecha','=',$fecha)->where('activo','=',1);
        $data = $request->all();
        if($data['servicio'] != 0){
            $consulta = $consulta->where('servicio', '=', $data['servicio']);
        }
        $inscripciones = $consulta->get();
        foreach($inscripciones as $inscripcion)
        {
            $inscripcion->infoasistente;
            $inscripcion->infoasistente->edad = Carbon::parse($inscripcion->infoasistente->nacimiento)->age;
            $inscripcion->asistencia = intval($inscripcion->asistencia);
        }
        return $inscripciones;
    }



    public function asistencia(Request $request)
    {
        $data = $request->all();
        $inscripcion = Inscripcion::find($data['id']);
        $inscripcion->asistencia = $data['asistencia'];
        $inscripcion->save();
        return 1;
    }


    public function asistentes()
    {
        $asistentes =  Asistente::all();
        foreach($asistentes as $asistente)
        {
            $asistente->edad = Carbon::parse($asistente->nacimiento)->age;
        }
        return $asistentes;
    }



    public function inscripcionFecha(Request $request)
    {
        $data = $request->all();
        $inscripciones = [];
        $consulta = Inscripcion::where('activo', '=', 1);
        if($data['fecha'] != null and $data['servicio'] != 0)
        {
            $date = date_create($data['fecha']);
            $parsed = date_format($date, "Y-m-d");
            $data['fecha'] = $parsed;
            $consulta = Inscripcion::where('fecha','=',$data['fecha'])->where('servicio','=',$data['servicio'])->where('activo', '=', 1);
        }
        if($data['fecha'] != null && $data['servicio'] == 0)
        {
            $date = date_create($data['fecha']);
            $parsed = date_format($date, "Y-m-d");
            $data['fecha'] = $parsed;
            $consulta = Inscripcion::where('fecha','=',$data['fecha'])->where('activo', '=', 1);
        }

        $inscripciones = $consulta->get();
        foreach($inscripciones as $inscripcion)
        {
            $inscripcion->infoasistente;
            $inscripcion->infoasistente->edad = Carbon::parse($inscripcion->infoasistente->nacimiento)->age;
            $inscripcion->asistencia = intval($inscripcion->asistencia);
        }
        return $inscripciones;

    }
}

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
        $date = date_create($data['nacimiento']);
        $parsed = date_format($date, "Y-m-d");
        $data['nacimiento'] = $parsed;
        $edad = Carbon::parse($data['nacimiento'])->age;
        $data['nino'] = 0;
        $data['fecha'] = Carbon::parse('this sunday')->toDateString();
        if($edad < 12)
            $data['nino'] = 1;



        //VALIDAR QUE EL USUARIO NO TENGA UNA INSCRIPCIÓN ACTIVA PARA EL MISMO DÍA
        $existente = Inscripcion::where('cedula','=',$data['cedula'])->where('fecha','=',$data['fecha'])->where('activo','=','1')->get();
        if(count($existente))
        {
            return [
                "error" => 1,
                "msg" => 'Ya tienes una inscripción registrada para este domingo',
            ];
        }


        //VALIDAR ENCUESTA COVID
        if($data['covid1'] || count($data['covid2']) || $data['covid3'] || $data['covid4'])
            return [
                "error" => 1,
                "msg" => 'Por motivos de bioseguridad, no podemos completar tu inscripción. Te pedimos que te quedes en casa, consultes a tu médico de confianza
                y te conectes a la iglesia en línea a través de nuestro canal de Youtube.',
            ];



        //VALIDAR CANTIDAD DE ASISTENTES
        //Niños
        if($data['nino'] == 1){
            $inscritos = Inscripcion::where('nino', '=', 1)->where('fecha','=',$data['fecha'])->where('activo','=',1)->where('servicio','=',$data['servicio'])->get();
            if(count($inscritos) >= 20)
            return [
                "error" => 1,
                "msg" => 'El aforo de niños para este horario ya se ha completado. Te recomendamos que selecciones un horario diferente o que te quedes en casa y te conectes
                a la iglesia en línea a través de nuestro canal de Youtube.',
            ];
        }
        //Adultos
        if($data['nino'] == 0){
            $inscritos = Inscripcion::where('nino', '=', 0)->where('fecha','=',$data['fecha'])->where('activo','=',1)->where('servicio','=',$data['servicio'])->get();
            if(count($inscritos) >= 60)
            return [
                "error" => 1,
                "msg" => 'El aforo de adultos para este horario ya se ha completado. Te recomendamos que selecciones un horario diferente o que te quedes en casa y te conectes
                a la iglesia en línea a través de nuestro canal de Youtube.',
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
        $fecha = Carbon::parse('this sunday')->toDateString();
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
        $fecha = Carbon::parse('this sunday');
        $parsed = date_format($fecha, "d/m/Y");
        return $parsed;
    }



    public function inscripciones()
    {
        $fecha = Carbon::parse('this sunday')->toDateString();
        $inscripciones = Inscripcion::where('fecha','=',$fecha)->where('activo','=',1)->get();
        foreach($inscripciones as $inscripcion)
        {
            $inscripcion->infoasistente;
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
        return Asistente::all();
    }
}

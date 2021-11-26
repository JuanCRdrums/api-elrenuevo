<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aniversario;
use App\Models\Asistente;
use Carbon\Carbon;

class AniversarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        if($edad < 12)
            $data['nino'] = 1;



        if($data['servicio'] == 1 || $data['servicio'] == 2)
        {
            //VALIDAR QUE EL USUARIO NO TENGA UNA INSCRIPCIÓN ACTIVA PARA EL MISMO DÍA
            $existente = Aniversario::where('cedula','=',$data['cedula'])->where('servicio','=',$data['servicio'])->where('activo','=','1')->get();
            if(count($existente))
            {
                if($data['servicio'] == 1)
                {
                    return [
                        "error" => 1,
                        "msg" => 'Ya tienes una inscripción registrada para el sábado',
                    ];
                }

                if($data['servicio'] == 2)
                {
                    return [
                        "error" => 1,
                        "msg" => 'Ya tienes una inscripción registrada para el domingo',
                    ];
                }
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
                $inscritos = Aniversario::where('nino', '=', 1)->where('servicio','=',$data['servicio'])->where('activo','=',1)->get();
                if(count($inscritos) >= 50)
                return [
                    "error" => 1,
                    "msg" => 'El aforo de niños para este servicio ya se ha completado. Te recomendamos que selecciones un servicio diferente o que te quedes en casa y te conectes
                    a la iglesia en línea a través de nuestro canal de Youtube.',
                ];
            }
            //Adultos
            if($data['nino'] == 0){
                $inscritos = Aniversario::where('nino', '=', 0)->where('activo','=',1)->where('servicio','=',$data['servicio'])->get();
                if(count($inscritos) >= 120)
                return [
                    "error" => 1,
                    "msg" => 'El aforo de adultos para este servicio ya se ha completado. Te recomendamos que selecciones un servicio diferente o que te quedes en casa y te conectes
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

            Aniversario::create($data);
            Asistente::updateOrCreate( ['cedula' => $data['cedula']], $dataUsuario);


            return [
                "error" => 0,
                "msg" => 'Registro correcto',
            ];
        }

        if($data['servicio'] == 3)
        {
            $sabado = 1;
            $domingo = 1;
            $existente_sabado = 0;
            $existente_domingo = 0;
            $aforo_sabado = 0;
            $aforo_domingo = 0;
            for($i = 1; $i <= 2; $i++)
            {
                //VALIDAR QUE EL USUARIO NO TENGA UNA INSCRIPCIÓN ACTIVA PARA EL MISMO DÍA
                $existente = Aniversario::where('cedula','=',$data['cedula'])->where('servicio', '=', $i)->where('activo','=','1')->get();
                if(count($existente))
                {
                    if($i == 1)
                    {
                        $sabado = false;
                        $existente_sabado = true;
                    }

                    if($i == 2)
                    {
                        $domingo = false;
                        $existente_domingo = true;
                    }
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
                    $inscritos = Aniversario::where('nino', '=', 1)->where('servicio','=',$i)->where('activo','=',1)->get();
                    if(count($inscritos) >= 50)
                    {
                        if($i == 1)
                        {
                            $sabado = false;
                            $aforo_sabado = true;
                        }
                        if($i == 2)
                        {
                            $domingo = false;
                            $aforo_domingo = true;
                        }
                    }
                }
                //Adultos
                if($data['nino'] == 0){
                    $inscritos = Aniversario::where('nino', '=', 0)->where('activo','=',1)->where('servicio','=',$i)->get();
                    if(count($inscritos) >= 120)
                    {

                        if($i == 1)
                        {
                            $sabado = false;
                            $aforo_sabado = true;
                        }
                        if($i == 2)
                        {
                            $domingo = false;
                            $aforo_domingo = true;
                        }
                    }
                }


                $dataUsuario = [
                    'cedula' => $data['cedula'],
                    'nombre' => $data['nombre'],
                    'nacimiento' => $data['nacimiento'],
                    'telefono' => $data['telefono'],
                    'email' => $data['email'],
                    'habilitado' => $data['habilitado']
                ];

                if($i == 1 && $sabado)
                {
                    $data['servicio'] = 1;
                    Aniversario::create($data);
                }
                if($i == 2 && $domingo)
                {
                    $data['servicio'] = 2;
                    Aniversario::create($data);
                }
                Asistente::updateOrCreate( ['cedula' => $data['cedula']], $dataUsuario);
            }


            if($sabado && $domingo)
            {
                return [
                    "error" => 0,
                    "msg" => "Registro exitoso"
                ];
            }

            if($sabado && !$domingo)
            {
                if($existente_domingo)
                {
                    return [
                        "error" => 0,
                        "msg" => "Ya tienes una inscripción para el domingo. Te inscribimos para el día sábado."
                    ];
                }

                if($aforo_domingo)
                {
                    //niño
                    if($data['nino'] == 1)
                    {
                        return [
                            "error" => 1,
                            "msg" => "El aforo de niños para el domingo ya está lleno. Solamente fue posible inscribirte para el día sábado."
                        ];
                    }
                    //adulto
                    if($data['nino'] == 0)
                    {
                        return [
                            "error" => 1,
                            "msg" => "El aforo de adultos para el domingo ya está lleno. Solamente fue posible inscribirte para el día sábado."
                        ];
                    }
                }
            }

            if(!$sabado && $domingo)
            {
                if($existente_sabado)
                {
                    return [
                        "error" => 0,
                        "msg" => "Ya tienes una inscripción para el sábado. Te inscribimos para el día domingo."
                    ];
                }

                if($aforo_sabado)
                {
                    //niño
                    if($data['nino'] == 1)
                    {
                        return [
                            "error" => 1,
                            "msg" => "El aforo de niños para el sábado ya está lleno. Te inscribimos para el día domingo."
                        ];
                    }
                    //adulto
                    if($data['nino'] == 0)
                    {
                        return [
                            "error" => 1,
                            "msg" => "El aforo de adultos para el sábado ya está lleno. Solamente fue posible inscribirte para el día domingo."
                        ];
                    }
                }
            }

            if(!$sabado && !$domingo)
            {
                if($existente_sabado)
                {
                    $msg = "Ya tienes una inscripción activa para el día sábado ";
                    if($existente_domingo)
                    {
                        $msg .= "y para el día domingo.";
                    }
                    if($aforo_domingo)
                    {
                        $msg .= "y el aforo para el día domingo ya está lleno.";
                    }
                }

                if($aforo_sabado)
                {
                    if($aforo_domingo)
                    {
                        $msg = "El aforo para ambas reuniones ya está lleno.";
                    }

                    if($existente_domingo)
                    {
                        $msg = "El aforo para la reunión del sábado ya está lleno y ya tienes una inscripción activa para el día domingo";
                    }
                }

                return [
                    "error" => 1,
                    "msg" => $msg,
                ];
            }

            return [
                "error" => 0,
                "msg" => "Registro exitoso",
            ];
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inscritos(Request $request)
    {
        $data = $request->all();
        $inscripciones = Aniversario::where('servicio', '=', $data['servicio'])->get();
        foreach($inscripciones as $inscripcion)
        {
            $inscripcion->infoasistente;
            $inscripcion->infoasistente->edad = Carbon::parse($inscripcion->infoasistente->nacimiento)->age;
        }
        return $inscripciones;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

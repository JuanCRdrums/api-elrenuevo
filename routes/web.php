<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InscripcionesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OpcionesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::post('/api/inscripciones/store',[InscripcionesController::class, 'store']);
Route::post('/api/inscripciones/datosAsistente',[InscripcionesController::class, 'datosBasicos']);
Route::post('/api/inscripciones/consultar',[InscripcionesController::class, 'consultar']);
Route::post('/api/inscripciones/cancelar',[InscripcionesController::class, 'cancelar']);
Route::post('/api/inscripciones/asistencia',[InscripcionesController::class, 'asistencia']);
Route::post('/api/inscripciones/asistenciaFecha',[InscripcionesController::class, 'inscripcionFecha']);
Route::get('/api/inscripciones/fechaActiva',[InscripcionesController::class, 'fechaActiva']);
Route::post('/api/inscripciones',[InscripcionesController::class, 'inscripciones']);
Route::get('/api/inscripciones/asistentes',[InscripcionesController::class, 'asistentes']);


//OPCIONES
Route::get('/api/opciones/{opcion}',[OpcionesController::class, 'opcion']);
Route::post('/api/opciones/set',[OpcionesController::class, 'setOption']);


Route::post('/api/login',[AuthController::class, 'login']);


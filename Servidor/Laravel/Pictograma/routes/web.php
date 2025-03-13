// routes/web.php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Ejercicio 1: Listado de pictogramas
Route::get('/pictogramas', [AgendaController::class, 'listadoPictogramas'])->name('pictogramas.listado');

// Ejercicio 2: Formulario para insertar nueva entrada en la agenda
Route::get('/agenda/crear', [AgendaController::class, 'formularioAgenda'])->name('agenda.formulario');
Route::post('/agenda/guardar', [AgendaController::class, 'guardarAgenda'])->name('agenda.guardar');

// Ejercicio 3: Consultar agenda
Route::get('/agenda/consultar', [AgendaController::class, 'consultarAgendaForm'])->name('agenda.consultar');
Route::post('/agenda/mostrar', [AgendaController::class, 'mostrarAgenda'])->name('agenda.mostrar');
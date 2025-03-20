<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PictogramaController;

Route::get('/', [PictogramaController::class, 'listarPictogramas'])->name('pictogramas.listado');

// Ejercicio 1: Listado de pictogramas
Route::get('/pictogramas', [PictogramaController::class, 'listarPictogramas'])->name('pictogramas.listado');

// Ejercicio 2: Formulario y guardar agenda
Route::get('/agenda/crear', [PictogramaController::class, 'formularioAgenda'])->name('agenda.formulario');
Route::post('/agenda/guardar', [PictogramaController::class, 'guardarAgenda'])->name('agenda.guardar');

// Ejercicio 3: Mostrar agenda
Route::get('/agenda/mostrar', [PictogramaController::class, 'formularioMostrarAgenda'])->name('agenda.mostrar');
Route::post('/agenda/mostrar', [PictogramaController::class, 'mostrarAgenda'])->name('agenda.mostrar.post');
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PictogramaController;

Route::get('/', [PictogramaController::class, 'index'])->name('pictogramas.index');
Route::get('/agenda/create', [PictogramaController::class, 'create'])->name('pictogramas.create');
Route::post('/agenda/store', [PictogramaController::class, 'store'])->name('pictogramas.store');
Route::get('/agenda/show', [PictogramaController::class, 'showAgenda'])->name('pictogramas.showAgenda');
Route::post('/agenda/filter', [PictogramaController::class, 'filterAgenda'])->name('pictogramas.filterAgenda');
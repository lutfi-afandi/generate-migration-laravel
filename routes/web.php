<?php

use App\Http\Controllers\MigrasiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/migrasi', [MigrasiController::class, 'index']);
Route::post('/migrasi/generate', [MigrasiController::class, 'generate'])->name('migrasi.generate');
Route::get('/migrasi/all/{database}', [MigrasiController::class, 'all']);


Route::post('/migrasi/tables', [MigrasiController::class, 'tables']);
Route::post('/migrasi/generate', [MigrasiController::class, 'generate'])->name('migrasi.generate');

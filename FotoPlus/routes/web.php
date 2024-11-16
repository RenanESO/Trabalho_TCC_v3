<?php

use Illuminate\Support\Facades\Route;
use App\Services\DownloadController;
use Illuminate\Support\Facades\Auth;

// Rota de download da pasta
Route::get('/download-zip/{user_id}', [DownloadController::class, 'download'])->name('download-zip');
Route::get('/download-and-redirect/{user_id}', [DownloadController::class, 'downloadAndRedirect'])->name('download-and-redirect');

// Rota para a pagina inicial do projeto
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rota de Ajuda
Route::get('/help', function () {
    return view('help');
})->middleware('auth')->name('help');

// Rota de Treinamento
Route::get('/training', function () {
    return view('training');
})->middleware('auth')->name('training');

// Rota de Organizar
Route::get('/organize', function () {
    return view('organize');
})->middleware('auth')->name('organize');

// Rota de Duplicidade
Route::get('/duplicity', function () {
    return view('duplicity');
})->middleware('auth')->name('duplicity');

// Rota de Dashboard
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::post('/logout', function () {
    Auth::logout(); // Desloga o usuário
    return view('welcome');
})->name('logout');




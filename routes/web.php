<?php

use App\Http\Controllers\PeopleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use App\Http\Controllers\ProtocolController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Pessoas:
    Route::get('/pessoas', [PeopleController::class, 'index'])->name('people.index');
    Route::post('/pessoa', [PeopleController::class, 'store'])->name('people.store')->middleware([HandlePrecognitiveRequests::class]);
    Route::get('/editar-pessoa/{id}', [PeopleController::class, 'show'])->name('people.show');
    Route::put('/editar-pessoa/{id}', [PeopleController::class, 'update'])->name('people.update')->middleware([HandlePrecognitiveRequests::class]);
    Route::delete('/deletar-pessoa/{id}', [PeopleController::class, 'destroy'])->name('people.destroy')->middleware([HandlePrecognitiveRequests::class]);

    // Protocolos:
    Route::get('/protocolo', [ProtocolController::class, 'index'])->name('protocols.index');
    Route::post('/protocolo', [ProtocolController::class, 'store'])->name('protocol.store')->middleware([HandlePrecognitiveRequests::class]);
    Route::get('/editar-protocolo/{id}', [ProtocolController::class, 'show'])->name('protocol.show');
    Route::put('/editar-protocolo/{id}', [ProtocolController::class, 'update'])->name('protocol.update')->middleware([HandlePrecognitiveRequests::class]);
    Route::delete('/deletar-protocolo/{id}', [ProtocolController::class, 'destroy'])->name('protocol.destroy');
});
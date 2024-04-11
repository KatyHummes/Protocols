<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PeopleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use App\Http\Controllers\ProtocolController;
use App\Http\Controllers\ReportController;
use App\Models\User;
use Illuminate\Routing\RouteGroup;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        // 'canRegister' => Route::has('register'),
    ]);
})->name('welcome');

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
    Route::get('/protocolos', [ProtocolController::class, 'index'])->name('protocols.index');
    Route::post('/protocolo', [ProtocolController::class, 'store'])->name('protocol.store')->middleware([HandlePrecognitiveRequests::class]);
    Route::get('/editar-protocolo/{id}', [ProtocolController::class, 'show'])->name('protocol.show');
    Route::put('/editar-protocolo/{id}', [ProtocolController::class, 'update'])->name('protocol.update')->middleware([HandlePrecognitiveRequests::class]);
    Route::delete('/deletar-protocolo/{id}', [ProtocolController::class, 'destroy'])->name('protocol.destroy');

    // Autenticação:
    Route::get('/registro', [AuthController::class, 'create'])->name('create');
    Route::prefix('/usuarios')->name('users.')->group(function () {
        Route::post('/registro', [AuthController::class, 'register'])->name('register')->middleware([HandlePrecognitiveRequests::class]);
        Route::get('/', [AuthController::class, 'index'])->name('index');
        Route::get('/{user}', [AuthController::class, 'show'])->name('show');
        Route::post('/editar/{id}', [AuthController::class, 'update'])->name('update')->middleware([HandlePrecognitiveRequests::class]);
    });

    // Departamentos:
    Route::prefix('/departamentos')->name('departments.')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::post('/', [DepartmentController::class, 'store'])->name('store')->middleware([HandlePrecognitiveRequests::class]);
        Route::get('/{id}', [DepartmentController::class, 'show'])->name('show');
        Route::put('/{id}', [DepartmentController::class, 'update'])->name('update')->middleware([HandlePrecognitiveRequests::class]);
        Route::post('/access/{id}', [DepartmentController::class, 'access'])->name('access')->middleware([HandlePrecognitiveRequests::class]);
    });
    Route::post('access-destroy/{id}', [DepartmentController::class, 'destroy'])->name('access.destroy');

    // Acompanhamento:
    Route::post('/acompanhamento', [ReportController::class, 'store'])->name('store.Report')->middleware([HandlePrecognitiveRequests::class]);
});

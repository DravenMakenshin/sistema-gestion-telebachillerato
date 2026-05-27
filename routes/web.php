<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CentroController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\UserController;

// Rutas públicas (login, registro)
require __DIR__.'/auth.php';

// Ruta de prueba pública
Route::get('/test-db', function () {
    $centros = DB::table('centros')->count();
    $alumnos = DB::table('alumnos')->count();
    return response()->json([
        'status' => 'success',
        'message' => 'Conexión a base de datos exitosa',
        'centros' => $centros,
        'alumnos' => $alumnos
    ]);
});

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard / Home
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    
    // MÓDULO DE CENTROS
    Route::prefix('centros')->name('centros.')->group(function () {
        Route::get('/', [CentroController::class, 'index'])->name('index')->middleware('consultor');
        Route::get('/{id}', [CentroController::class, 'show'])->name('show')->middleware('consultor');
        Route::get('/json/list', [CentroController::class, 'getCentrosJson'])->name('json')->middleware('consultor');
    });
    
    // MÓDULO DE ALUMNOS
    Route::prefix('alumnos')->name('alumnos.')->group(function () {
        Route::get('/', [AlumnoController::class, 'index'])->name('index')->middleware('consultor');
        Route::get('/data', [AlumnoController::class, 'getData'])->name('data')->middleware('consultor');
        Route::get('/{id}', [AlumnoController::class, 'show'])->name('show')->middleware('consultor');
        Route::get('/{id}/edit', [AlumnoController::class, 'edit'])->name('edit')->middleware('admin');
        Route::put('/{id}', [AlumnoController::class, 'update'])->name('update')->middleware('admin');
    });
    
    // MÓDULO DE CALIFICACIONES
    Route::prefix('calificaciones')->name('calificaciones.')->group(function () {
        Route::get('/', [CalificacionController::class, 'index'])->name('index')->middleware('consultor');
        Route::get('/get-materias/{id_centro}', [CalificacionController::class, 'getMateriasByCentro'])->name('getMaterias')->middleware('consultor');
        Route::get('/get-alumnos/{id_centro}', [CalificacionController::class, 'getAlumnosByCentro'])->name('getAlumnos')->middleware('consultor');
        Route::get('/get-calificacion/{id_alumno}/{id_materia}', [CalificacionController::class, 'getCalificacion'])->name('getCalificacion')->middleware('consultor');
        Route::post('/save', [CalificacionController::class, 'save'])->name('save')->middleware('admin');
        Route::get('/list', [CalificacionController::class, 'list'])->name('list')->middleware('consultor');
    });
    
    // GESTIÓN DE USUARIOS (solo admin)
    Route::middleware(['admin'])->prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });
});
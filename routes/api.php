<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TipoDocumentoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ENDPOINT PARA REGISTRAR USUARIO
Route::post('register', [AuthController::class, 'register']);

// ENDPOINT PARA INICIAR SESSION
Route::post('login', [AuthController::class, 'login']);


// ENDPOINT PARA LISTAR LOS TIPOS DE DOCUMENTO
Route::get('documentos', [TipoDocumentoController::class, 'index']);


// RUTAS PROTEGIDAS
Route::middleware('auth:sanctum')->group(function () {

    // ENDPOINT DE LOS POSTS (GET, POST, PUT, DELETE)
    Route::apiResource('posts', PostController::class);

    // ENDPOINT PARA LA BUSQUEDA DE LOS POSTS POR TITULO
    Route::get('posts/search/', [PostController::class, 'search']);

    // ENDPOINT PARA CERRAR SESSION
    Route::get('logout', [AuthController::class, 'logout']);
});


// PARA PODER EJECUTAR LAS MIGRACIONES ABRE LA TERMINAL DE VS CODE Y EJECUTA ESTE COMANDO "php artisan migrate"

// AHORA BIEN, REVISA QUE ESTEN LAS TABLAS EN MYSQL Y SI YA ESTÁN EJECUTA LOS DATOS FALSOS(SEEDER) CON "php artisan db:seed"

// Y YA PUEDES PROBAR LOS ENDPOINT

// ME AVISAS CUALQUIER INQUIETUD

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\NovelController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ReadingController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\AuthController;

//Proporciona información del usuario autenticado
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Rutas con login
Route::middleware('auth:sanctum')->group(function () {

    //Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    //Actualizar perfil del usuario
    Route::put('/user', [AuthController::class, 'update']);

    //Añadir y eliminar favoritos
    Route::post('/novels/{id}/favourite', [FavouriteController::class, 'store']);
    Route::delete('/novels/{id}/favourite', [FavouriteController::class, 'destroy']);
    
    //Añadir, actualizar o eliminar lecturas/progreso
    Route::post('/chapters/{id}/read', [ReadingController::class, 'storeOrUpdate']);
    Route::delete('/chapters/{id}/read', [ReadingController::class, 'destroy']);
    Route::get('/readings', [ReadingController::class, 'index']);

    //Crea todas las rutas protegidas, menos las funciones index y show del controlador
    Route::apiResource('/novels', NovelController::class)->except(['index', 'show']);
    Route::apiResource('/chapters', ChapterController::class);
    //Crea solo las rutas store y update como protegidas
    Route::apiResource('/statistics', StatisticController::class)->only(['store', 'update']);

    //Subir y eliminar archivos multimedia
    Route::post('/novels/{id}/media', [NovelController::class, 'uploadMedia']);
    Route::delete('/novels/{id}/media/{mediaId}', [NovelController::class, 'deleteMedia']);
    Route::post('/chapters/{id}/media', [ChapterController::class, 'uploadMedia']);
    Route::delete('/chapters/{id}/media/{mediaId}', [ChapterController::class, 'deleteMedia']);
  
});

//Rutas sin login
//Registro y Login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Recuperar contraseña primero solicita el enlace y después ingresa la nueva contraseña
Route::post('/password/forgot', [AuthController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

//Mostrar estadísticas
Route::get('/statistics', [StatisticController::class, 'index']);

//Mostrar novelas y capítulos
Route::apiResource('/novels', NovelController::class)->only(['index', 'show']);

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

    //Añadir, eliminar y listar favoritos
    Route::post('/novels/{novel}/favourite', [FavouriteController::class, 'store']);
    Route::delete('/novels/{novel}/favourite', [FavouriteController::class, 'destroy']);
    Route::get('/favourites', [FavouriteController::class, 'index']);
    
    //Añadir, actualizar, mostrar o eliminar lecturas/progreso
    Route::post('/chapters/{chapter}/read', [ReadingController::class, 'storeOrUpdate']);
    Route::delete('/chapters/{chapter}/read', [ReadingController::class, 'destroy']);
    Route::get('/readings', [ReadingController::class, 'index']);
    Route::get('/readings/{reading}', [ReadingController::class, 'show']);

    //Montrar, listar, actualizar, guardar y eliminar capítulos de una novela
    Route::get('/novels/{novel}/chapters', [ChapterController::class, 'index']);
    Route::get('/novels/{novel}/chapters/{chapter}', [ChapterController::class, 'show']);
    Route::post('/novels/{novel}/chapters', [ChapterController::class, 'store']);
    Route::put('/novels/{novel}/chapters/{chapter}', [ChapterController::class, 'update']);
    Route::delete('/novels/{novel}/chapters/{chapter}', [ChapterController::class, 'destroy']);
    

    //Crea todas las rutas protegidas, menos las funciones index y show del controlador
    Route::apiResource('/novels', NovelController::class)->except(['index', 'show']);

    //Crea solo las rutas store y update como protegidas
    Route::apiResource('/statistics', StatisticController::class)->only(['store', 'update']);

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
Route::get('novelx/{novel}/statistics', [StatisticController::class, 'show']);

//Mostrar novelas
Route::apiResource('/novels', NovelController::class)->only(['index', 'show']);

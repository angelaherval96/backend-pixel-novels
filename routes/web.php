<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FavouriteController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/novels/{id}/favourite', [FavouriteController::class, 'store']);
    Route::delete('/novels/{id}/favourite', [FavouriteController::class, 'destroy']);
    
});
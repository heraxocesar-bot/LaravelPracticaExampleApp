<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class);

// Este es un ejemplo de api, devuelve un valor "quemado" en este caso un string(caracteres)
Route::get('soycesar', function () {
    return 'Que tal mi nombre es cesar';
});



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

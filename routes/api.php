<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\PropertyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class, 'register']);
Route::get('/logout', [LoginController::class, 'logout'])->middleware('auth:api');
Route::get('properties/latest',[PropertyController::class, 'latest']);
Route::get('/properties/filter', [PropertyController::class, 'filter']);
Route::get('/type/property', [PropertyController::class, 'getPropertyTypes']);
Route::get('/properties/search', [PropertyController::class, 'search']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('properties', [PropertyController::class, 'index']);
    Route::post('properties', [PropertyController::class, 'store']);
    Route::get('properties/{id}', [PropertyController::class, 'show']);
    Route::put('properties/{id}', [PropertyController::class, 'update']);
    Route::delete('properties/{id}', [PropertyController::class, 'destroy']);
});

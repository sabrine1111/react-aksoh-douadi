<?php

use App\Http\Controllers\api\admin\AdminController;
use App\Http\Controllers\api\user\favoriteController;
use App\Http\Controllers\api\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::get('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/my-favorite', [AuthController::class, 'favorite']);
    Route::post('/add-to-favorite', [favoriteController::class, 'addToFavorite']);
    Route::post('/remove-product-from-favorite', [AuthController::class, 'removeProductFromFavorite']);
});

    Route::middleware('auth:sanctum')->post('/product/add',[AdminController::class,'add']);
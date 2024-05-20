<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [UserController::class, 'login']); // No middleware (public login)

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/protected-resource', function () {
        // Protected resource accessible only with valid API token
        return response()->json(['message' => 'This is a protected resource']);
    });
});
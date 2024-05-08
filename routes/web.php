<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('PaginaPrincipal', [
    ]);
});

Route::get('/Listas', function () {
    return Inertia::render('Listas', [
    ]);
});

Route::get('/Perfil', function () {
    return Inertia::render('Perfil', [
    ]);
});

Route::get('/Loginsolydec', function () {
    return Inertia::render('Login', [
    ]);
});

Route::get('/Signup', function () {
    return Inertia::render('SignUp', [
    ]);
});

Route::get('/Loginsignup', function () {
    return Inertia::render('LoginSignUp', [
    ]);
});




Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
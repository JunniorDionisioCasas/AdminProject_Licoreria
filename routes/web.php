<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/productos', function () {
    return view('administrative/products/products');
});

Route::get('/categorias', function () {
    return view('administrative/products/categorias');
});

Route::get('/marcas', function () {
    return view('administrative/products/marcas');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/home', function () {
        return view('dashboard/home');
    })->name('home');
});

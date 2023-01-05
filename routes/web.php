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

Route::permanentRedirect('/register', '/login');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/home', function () {
        return view('dashboard/home');
    })->name('home');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // products
    Route::get('/productos', function () {
        return view('administrative/products/products');
    });

    Route::get('/categorias', function () {
        return view('administrative/products/categorias');
    });

    Route::get('/marcas', function () {
        return view('administrative/products/marcas');
    });

    Route::get('/proveedores', function () {
        return view('administrative/products/proveedores');
    });

    // sales
    Route::get('/pedidos', function () {
        return view('administrative/sales/pedidos');
    });

    Route::get('/registrar-nuevo-pedido', function () {
        return view('administrative/sales/registrar-nuevo-pedido');
    });
    
    Route::get('/tipos-pedidos', function () {
        return view('administrative/sales/tipos-pedidos');
    });

    Route::get('/descuentos', function () {
        return view('administrative/sales/descuentos');
    });

    Route::get('/tipos-descuentos', function () {
        return view('administrative/sales/tipos-descuentos');
    });

    // human_resources
    Route::get('/clientes', function () {
        return view('administrative/human_resources/clientes');
    });

    Route::get('/empleados', function () {
        return view('administrative/human_resources/empleados');
    });
    
    Route::get('/cargos', function () {
        return view('administrative/human_resources/cargos');
    });

    // reportes
    Route::get('/reporte-ventas', function () {
        return view('administrative/reports/reporte-ventas');
    });
});

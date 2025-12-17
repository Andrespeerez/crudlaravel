<?php
/**
 * *ROUTES*
 * 
 * *DESCRIPCIÓN*
 * Genera las rutas que se manejan en el  
 * 
 * @author Andrés Pérez Guardiola 2º DAW Semi
 */

use App\Http\Controllers\FacturalineasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\FacturasController;
use App\Http\Controllers\ArticulosController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

// Mediante Resource -> Crea automáticamente todas las listas
Route::resource('clientes', ClientesController::class)->middleware('auth');
Route::get('/facturas/cliente/{cliente_id}', [FacturasController::class, 'facturascliente'])->middleware('auth');
Route::resource('facturas', FacturasController::class)->middleware('auth');
Route::resource('articulos', ArticulosController::class)->middleware('auth');
Route::resource('facturalineas', FacturalineasController::class)->middleware('auth');
Route::get('/facturalineas/factura/{factura_id}', [FacturalineasController::class, 'facturalineasfactura'])->middleware('auth')->name('facturalineas.factura');


Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// FORMAS ALTERNATIVAS

// Llamadas directas a las vistas
/*


Route::get('/clientes', function() {
    return view('clientes.index');
});

Route::get('/clientes/create', function() {
    return view('clientes.create');
});

Route::get('/clientes/edit', function() {
    return view('clientes.edit');
});
*/

// Manual -> Llamadas a los métodos del controlador
/*
Route::get('/clientes', [ClientesController:: class, 'index']);
Route::get('/clientes/create', [ClientesController:: class, 'create']);
Route::get('/clientes/edit', [ClientesController:: class, 'edit']);
*/

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
    return view('home');
});


Route::get('/modadmin', function () {
    return view('mod_admin.productos.index');
});



Route::get('/modcaja',  'VentasController@create'  );




Route::get('/clientes',   'ClientesController@index' );
Route::post('/clientes/buscar',   'ClientesController@index' );
Route::get('/clientes/create',    'ClientesController@create' );
Route::post('/clientes',    'ClientesController@create' );
Route::get('/clientes/update/{id}',    'ClientesController@update' );
Route::put('/clientes',    'ClientesController@update' );
Route::delete('/clientes/{id}',    'ClientesController@delete' );


Route::get('/proveedores',   'ProveedoresController@index' );
Route::post('/proveedores/buscar',   'ProveedoresController@index' );
Route::get('/proveedores/create',    'ProveedoresController@create' );
Route::post('/proveedores',    'ProveedoresController@create' );
Route::get('/proveedores/update/{id}',    'ProveedoresController@update' );
Route::put('/proveedores',    'ProveedoresController@update' );
Route::delete('/proveedores/{id}',    'ProveedoresController@delete' );



Route::get('/productos',   'ProductosController@index' );
Route::post('/productos/buscar',   'ProductosController@index' );
//Route::get('/productos/buscar',   'ProductosController@index' );
Route::get('/productos/create',    'ProductosController@create' );
Route::post('/productos',    'ProductosController@create' );
Route::get('/productos/update/{id}',    'ProductosController@update' );
Route::get('/productos/get/{id}',    'ProductosController@get' );
Route::put('/productos',    'ProductosController@update' );
Route::delete('/productos/{id}',    'ProductosController@delete' );

Route::get('/materia-prima',   'MateriaprimaController@index' );
Route::post('/materia-prima/buscar',   'MateriaprimaController@index' );
//Route::get('/materia-prima/buscar',   'MateriaprimaController@index' );
Route::get('/materia-prima/create',    'MateriaprimaController@create' );
Route::post('/materia-prima',    'MateriaprimaController@create' );
Route::get('/materia-prima/update/{id}',    'MateriaprimaController@update' );
Route::put('/materia-prima',    'MateriaprimaController@update' );
Route::get('/materia-prima/get/{id}',    'MateriaprimaController@get' );
Route::delete('/materia-prima/{id}',    'MateriaprimaController@delete' );



Route::get('/sucursal',   'SucursalController@index' );
Route::post('/sucursal/buscar',   'SucursalController@index' );
Route::get('/sucursal/buscar',   'SucursalController@index' );
Route::get('/sucursal/create',    'SucursalController@create' );
Route::post('/sucursal',    'SucursalController@create' );
Route::get('/sucursal/update/{id}',    'SucursalController@update' );
Route::put('/sucursal',    'SucursalController@update' );
Route::delete('/sucursal/{id}',    'SucursalController@delete' );


Route::get('/cargo',   'CargosController@index' );
Route::post('/cargo/buscar',   'CargosController@index' );
Route::get('/cargo/buscar',   'CargosController@index' );
Route::get('/cargo/create',    'CargosController@create' );
Route::post('/cargo',    'CargosController@create' );
Route::get('/cargo/update/{id}',    'CargosController@update' );
Route::put('/cargo',    'CargosController@update' );
Route::delete('/cargo/{id}',    'CargosController@delete' );

Route::get('/familia',   'FamiliaController@index' );
Route::post('/familia/buscar',   'FamiliaController@index' );
Route::get('/familia/buscar',   'FamiliaController@index' );
Route::get('/familia/create',    'FamiliaController@create' );
Route::post('/familia',    'FamiliaController@create' );
Route::get('/familia/update/{id}',    'FamiliaController@update' );
Route::put('/familia',    'FamiliaController@update' );
Route::delete('/familia/{id}',    'FamiliaController@delete' );



Route::get('/deposito',   'DepositoController@index' );//Solo una vista
Route::get('/deposito/{contexto}',   'DepositoController@index' );
Route::post('/deposito/{contexto}',   'DepositoController@index' ); 
Route::get('/deposito-compra/{contexto}',   'DepositoController@compra' );
Route::post('/deposito-compra/{contexto}',   'DepositoController@compra' );
Route::get('/deposito-recepcion/{contexto}',   'DepositoController@recepcion' );
Route::post('/deposito-recepcion/{contexto}',   'DepositoController@recepcion' );
Route::get('/deposito-salida/{contexto}',   'DepositoController@salida' );
Route::post('/deposito-salida',   'DepositoController@salida' );
 

Route::get('/ventas',   'VentasController@create' );
Route::post('/ventas',   'VentasController@create' );


Route::get('/admin', function () {
    return view('mod_admin.welcome');
});


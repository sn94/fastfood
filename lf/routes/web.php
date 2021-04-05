<?php

use App\Models\Ciudades;
use App\Models\Receta;
use App\Models\Stock;
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


Route::get('/test', function () {
    return view("buscador/Buscador");
}  );


Route::get('/', function () {


    if (session("NIVEL") == "CAJA")
        return view('welcome_caja');
    if (session("NIVEL") == "SUPER")
        return view('welcome_admin');
})->middleware("auth.Caja");


Route::get('/modulo-caja', function () {
    return view('welcome_caja');
})->middleware("auth.Caja");

Route::get('/modulo-administrativo', function () {
    return view('welcome_admin');
})->middleware("auth.Admin");



/** utiles */
/**Buscador generico de productos preventa , elaborados,  materia prima, y a veces Activo fijo */
Route::get('/buscador-items', function () {
    $items = (new Stock())->where("tipo", "PE")->get();
    return view('buscador.items',  ['items' => $items]);
})->middleware("auth.Caja");
Route::get('/buscador-items/{TIPO}', function ($TIPO) {
    $items = (new Stock())->where("tipo", $TIPO)->get();
    return view('buscador.items',  ['TIPO' =>  $TIPO, 'items' => $items]);
})->middleware("auth.Caja");
/**Buscador personas */
Route::get('/buscador-personas/{TIPO}', function ($TIPO) {
    return view('buscador.personas',  ['TIPO' => $TIPO]);
})->middleware("auth.Caja");

/**Buscador GENERICO */
Route::get('/buscador-generico/{TIPO}', function ($TIPO) {
    return view('buscador.generico',  ['TIPO' => $TIPO]);
})->middleware("auth.Caja");


/**
 *Ciudades
 */
Route::get('/ciudades', function () {

    return response()->json(Ciudades::get());
})->middleware("auth.Admin");


/**administradores*/
Route::group(['prefix' => "clientes",   'middleware' => ['auth.Caja']], function () { //Route::prefix('clientes')
    Route::get('/',   'ClientesController@index');
    Route::post('/buscar',   'ClientesController@index');
    Route::get('/create',    'ClientesController@create');
    Route::post('/',    'ClientesController@create');
    Route::get('/update/{id}',    'ClientesController@update');
    Route::put('/',    'ClientesController@update');
    Route::delete('/{id}',    'ClientesController@delete');
});

 

Route::group(['prefix' => "proveedores",   'middleware' => ['auth.Admin']], function () { //Route::prefix('proveedores')
    Route::get('/',   'ProveedoresController@index');
    Route::post('/buscar',   'ProveedoresController@index');
    Route::get('/create',    'ProveedoresController@create');
    Route::post('/',    'ProveedoresController@create');
    Route::get('/update/{id}',    'ProveedoresController@update');
    Route::put('/',    'ProveedoresController@update');
    Route::delete('/{id}',    'ProveedoresController@delete');
});


Route::group(['prefix' => "stock",   'middleware' => ['auth.Caja']], function () {
    Route::get('/',   'StockController@index');

    //Route::get('/productos/buscar',   'StockController@index' );
    Route::get('/create',    'StockController@create');
    Route::post('/',    'StockController@create');
    Route::get('/update/{id}',    'StockController@update');
    Route::get('/get/{id}',    'StockController@get');
    Route::put('/',    'StockController@update');
    Route::delete('/{id}',    'StockController@delete');
    Route::get('/receta', function () {
        return view('stock.form_receta');
    });
    Route::get('/receta/{STOCKID}', function ($STOCKID) {
        $receta = Receta::where("REGNRO", $STOCKID)->get();
        return view('stock.form_receta',  ['RECETA' =>   $receta]);
    });

    Route::get('/',   'StockController@index');

    Route::get('/buscar/{TIPO}',   'StockController@index');
    Route::get('/buscar',   'StockController@index');
    Route::post('/buscar',   'StockController@index');
});





Route::group(['prefix' => "stock",   'middleware' => ['auth.Caja']], function () {
});




Route::group(['prefix' => "sucursal",   'middleware' => ['auth.Admin']], function () {
    Route::get('/',   'SucursalController@index');
    Route::post('/buscar',   'SucursalController@index');
    Route::get('/buscar',   'SucursalController@index');
    Route::get('/create',    'SucursalController@create');
    Route::post('/',    'SucursalController@create');
    Route::get('/update/{id}',    'SucursalController@update');
    Route::put('/',    'SucursalController@update');
    Route::delete('/{id}',    'SucursalController@delete');
});



Route::group(['prefix' => "usuario",   'middleware' => ['auth.Admin']],  function () {
    Route::get('/',   'UsuariosController@index');
    Route::post('/buscar',   'UsuariosController@index');
    Route::get('/buscar',   'UsuariosController@index');
    Route::get('/create',    'UsuariosController@create');
    Route::post('/',    'UsuariosController@create');
    Route::get('/update/{id}',    'UsuariosController@update');
    Route::put('/',    'UsuariosController@update');
    Route::delete('/{id}',    'UsuariosController@delete');
});



Route::prefix("usuario")->group(function () {
    Route::get('/sign-in',   'UsuariosController@sign_in');
    Route::post('/sign-in',   'UsuariosController@sign_in');
    Route::get('/sign-out',   'UsuariosController@sign_out');
});
Route::group(['prefix' => 'caja', 'middleware' => ['auth.Admin']], function () {
    Route::get('/',   'CajaController@index');
    Route::post('/buscar',   'CajaController@index');
    Route::get('/buscar',   'CajaController@index');
    Route::get('/create',    'CajaController@create');
    Route::post('/',    'CajaController@create');
    Route::get('/update/{id}',    'CajaController@update');
    Route::put('/',    'CajaController@update');
    Route::delete('/{id}',    'CajaController@delete');
});


Route::group(['prefix' => 'turno', 'middleware' => ['auth.Admin']], function () {
    Route::get('/',   'TurnosController@index');
    Route::post('/buscar',   'TurnosController@index');
    Route::get('/buscar',   'TurnosController@index');
    Route::get('/create',    'TurnosController@create');
    Route::post('/',    'TurnosController@create');
    Route::get('/update/{id}',    'TurnosController@update');
    Route::put('/',    'TurnosController@update');
    Route::delete('/{id}',    'TurnosController@delete');
});

Route::group(['prefix' => 'niveles', 'middleware' => ['auth.Admin']], function () {
    Route::get('/',   'NivelesController@index');
    Route::post('/buscar',   'NivelesController@index');
    Route::get('/buscar',   'NivelesController@index');
    Route::get('/create',    'NivelesController@create');
    Route::post('/',    'NivelesController@create');
    Route::get('/update/{id}',    'NivelesController@update');
    Route::put('/',    'NivelesController@update');
    Route::delete('/{id}',    'NivelesController@delete');
});

Route::group(['prefix' => 'cargo', 'middleware' => ['auth.Admin']], function () {
    Route::get('/',   'CargosController@index');
    Route::post('/buscar',   'CargosController@index');
    Route::get('/buscar',   'CargosController@index');
    Route::get('/create',    'CargosController@create');
    Route::post('/',    'CargosController@create');
    Route::get('/update/{id}',    'CargosController@update');
    Route::put('/',    'CargosController@update');
    Route::delete('/{id}',    'CargosController@delete');
});

Route::group(['prefix' => 'medidas', 'middleware' => ['auth.Admin']], function () {
    Route::get('/',   'MedidasController@index');
    Route::post('/buscar',   'MedidasController@index');
    Route::get('/buscar',   'MedidasController@index');
    Route::get('/create',    'MedidasController@create');
    Route::post('/',    'MedidasController@create');
    Route::get('/update/{id}',    'MedidasController@update');
    Route::put('/',    'MedidasController@update');
    Route::delete('/{id}',    'MedidasController@delete');
});


Route::group(['prefix' => 'ciudades', 'middleware' => ['auth.Admin']], function () {
    Route::get('/',   'CiudadController@index');
    Route::post('/buscar',   'CiudadController@index');
    Route::get('/buscar',   'CiudadController@index');
    Route::get('/create',    'CiudadController@create');
    Route::post('/',    'CiudadController@create');
    Route::get('/update/{id}',    'CiudadController@update');
    Route::put('/',    'CiudadController@update');
    Route::delete('/{id}',    'CiudadController@delete');
});

Route::group(['prefix' => 'familia', 'middleware' => ['auth.Admin']], function () {
    Route::get('/',   'FamiliaController@index');
    Route::post('/buscar',   'FamiliaController@index');
    Route::get('/buscar',   'FamiliaController@index');
    Route::get('/create',    'FamiliaController@create');
    Route::post('/',    'FamiliaController@create');
    Route::get('/update/{id}',    'FamiliaController@update');
    Route::put('/',    'FamiliaController@update');
    Route::delete('/{id}',    'FamiliaController@delete');
    Route::get('/posiciones',   'FamiliaController@posiciones');
    Route::post('/posiciones',   'FamiliaController@posiciones');
});


Route::group(['prefix' => 'parametros', 'middleware' => ['auth.Admin']], function () {
    Route::get('/',   'ParametrosController@create');
    Route::post('/',    'ParametrosController@create');
});



Route::group(['prefix' => 'compra', 'middleware' => ['auth.Caja']],   function () {
    Route::get('/index',   'CompraController@index');
    Route::post('/index',   'CompraController@index');
    Route::get('/filtrar',   'CompraController@filtrar');
    Route::get('/filtrar/{filtro}',   'CompraController@filtrar');
    Route::post('/filtrar',   'CompraController@filtrar');

    Route::get('/',   'CompraController@create');
    Route::post('/',   'CompraController@create');
    Route::post('/unitaria',   'CompraController@create_unitaria');
    Route::get('/{IDCOMPRA}',   'CompraController@update');
    Route::put('/',   'CompraController@update');
    Route::delete("/{IDCOMPRA}",   'CompraController@delete');
});




Route::group(['prefix' => 'pedidos', 'middleware' => ['auth.Caja']],   function () {
    Route::get('/',   'PedidosController@index'); //lista los productos vendidos por cantidad, con opcion de pedido y control de pedido

    Route::get('/list/{STOCKID}',   'PedidosController@list');
    Route::get('/create/{STOCKID}',   'PedidosController@create');
    Route::post('/create',   'PedidosController@create');
    Route::get('/recibir/{PEDIDOID}',   'PedidosController@recibir');
    Route::get('/recibidos',   'PedidosController@recibidos');
    Route::get('/aprobar/{id}',   'PedidosController@aprobar');
    Route::post('/aprobar',   'PedidosController@aprobar');
});



Route::prefix('deposito')->group(function () {


    Route::get('/recepcion',   'DepositoController@recepcion');
    Route::post('/recepcion',   'DepositoController@recepcion');
    //  Route::get('/pedido-a-deposito',   'DepositoController@pedido_a_deposito');
    //Route::post('/pedido-a-deposito',   'DepositoController@pedido_a_deposito');
    Route::get('/salida/{IDPRODUCCION}',   'SalidaController@create');
    Route::get('/salida',   'SalidaController@create');
    Route::post('/salida',   'SalidaController@create');

    Route::get('/ficha-produccion', 'DepositoController@ficha_produccion');
    Route::post('/ficha-produccion',   'DepositoController@ficha_produccion');
    Route::put('/ficha-produccion',   'DepositoController@ficha_produccion');
    Route::get('/ficha-produccion/{IDPRODUCCION}', 'DepositoController@ficha_produccion');
    Route::get('/fichas-de-produccion/{ESTADO}/{ACCION}', function ($ESTADO, $ACCION) {
        return view("deposito.ficha_produccion.index.index", ['ESTADO' => $ESTADO, 'ACCION' => $ACCION]);
    });

    Route::get('/nota-residuos/{IDPRODUCCION}',   'DepositoController@nota_residuos');
    Route::post('/nota-residuos',   'DepositoController@nota_residuos');
    Route::get('/remision-productos-terminados/{IDPRODUCCION}',   'DepositoController@remision_de_terminados');
    Route::post('/remision-productos-terminados',   'DepositoController@remision_de_terminados');
});


Route::prefix('ventas')->group(function () {
    Route::get('/index',   'VentasController@index');
    Route::get('/',   'VentasController@create');
    Route::post('/',   'VentasController@create');
    Route::get('/ticket/{id}',   'VentasController@ticket');
    Route::get('/anular/{id}',   'VentasController@anular');
    Route::get('/view/{id}',   'VentasController@view');
});



Route::group(['prefix' => 'sesiones', 'middleware' => ['auth.Admin']], function () {
    Route::get('/',   'SesionesController@index');
    Route::post('/',   'SesionesController@index');
});

Route::group(['prefix' => 'sesiones', 'middleware' => ['auth.Caja']], function () {

    Route::get('/list',   'SesionesController@index'); //Solo vera sesiones propias
    Route::post('/list',   'SesionesController@index'); //Solo vera sesiones propias

    //  Route::post('/buscar',   'SesionesController@index');
    Route::get('/create',    'SesionesController@create');
    Route::post('/create',    'SesionesController@create');

    Route::get('/cerrar',    'SesionesController@cerrar');
    Route::get('/cerrar/{SESIONID}',    'SesionesController@cerrar');
    Route::post('/cerrar',    'SesionesController@cerrar');

    Route::get('/informe-arqueo/{SESIONID}',    'SesionesController@totalesArqueo');

    //Route::delete('/{id}',    'SesionesController@delete');
});

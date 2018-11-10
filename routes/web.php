<?php

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
    return view('welcome');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//routes inventario
Route::get('/inventario', 'ProductoController@index');
Route::post('/inventario/crear', 'ProductoController@crear');
Route::post('/inventario/editar', 'ProductoController@postEditar');
Route::get('/inventario/editar/{id}', 'ProductoController@editar');
Route::get('/inventario/eliminar/{id}', 'ProductoController@eliminar');
Route::get('/inventario/buscarproducto/{id}', 'ProductoController@buscarproducto');

// routes proveedor
Route::get('/proveedor', 'ProveedorController@index');
Route::post('/proveedor/crear', 'ProveedorController@crear');
Route::post('/proveedor/editar', 'ProveedorController@postEditar');
Route::get('/proveedor/editar/{id}', 'ProveedorController@editar');
Route::get('/proveedor/eliminar/{id}', 'ProveedorController@eliminar');

//routes persona

Route::get('/persona/{tipo}', 'PersonaController@index');
Route::post('/persona/crear', 'PersonaController@crear');
Route::post('/persona/editar', 'PersonaController@postEditar');
Route::get('/persona/editar/{id}/{tipo}', 'PersonaController@editar');
Route::get('/persona/eliminar/{id}', 'PersonaController@eliminar');
Route::get('/persona/buscarcliente/{cedula}', 'PersonaController@buscarcliente');

//routes compromiso
Route::get('/compromiso', 'CompromisoController@index');
Route::get('/compromiso/create', 'CompromisoController@create');
Route::get('/compromiso/detalle/{id_compromiso}', 'CompromisoController@detalle');
Route::get('/compromiso/ajustar/{id_compromiso}/{option}', 'CompromisoController@ajustar');
Route::post('/compromiso/crear', 'CompromisoController@crear');
Route::post('/compromiso/editar', 'CompromisoController@postEditar');
Route::post('/compromiso/entregar', 'CompromisoController@postEntregar');
Route::post('/compromiso/penalizar', 'CompromisoController@postPenalizar');
Route::post('/compromiso/devolucion', 'CompromisoController@postDevolucion');
Route::post('/compromiso/filtrar', 'CompromisoController@postFiltrar');
Route::get('/compromiso/pendientes', 'CompromisoController@getPendientes');

Route::get('/compromiso/editar/{id}', 'CompromisoController@editar');
Route::get('/compromiso/eliminar/{id}', 'CompromisoController@eliminar');
Route::get('/compromiso/entregar/{id}', 'CompromisoController@entregar');
Route::get('/compromiso/penalizar/{id}', 'CompromisoController@penalizar');
Route::get('/compromiso/devolver/{id}', 'CompromisoController@devolver');
//routes compromiso
Route::get('/entrega', 'EntregaController@index');
Route::get('/entrega/create', 'EntregaController@create');
Route::get('/entrega/detalle/{id_compromiso}', 'EntregaController@detalle');
Route::post('/entrega/crear', 'EntregaController@crear');
Route::post('/entrega/editar', 'EntregaController@postEditar');
Route::get('/entrega/editar/{id}', 'EntregaController@editar');
Route::get('/entrega/eliminar/{id}', 'EntregaController@eliminar');


//routes salidas
Route::get('/salida', 'SalidaController@index');
Route::get('/salida/create', 'SalidaController@create');
Route::post('/salida/crear', 'SalidaController@crear');
Route::post('/salida/editar', 'SalidaController@postEditar');
Route::get('/salida/editar/{id}', 'SalidaController@editar');
Route::get('/salida/eliminar/{id}', 'SalidaController@eliminar');

//routes cierre
Route::get('/cierre', 'CierreController@index');
Route::post('/cierre/generar', 'CierreController@generar');

Route::get('/novedad', 'NovedadController@index');
Route::post('/novedad/create', 'NovedadController@create');

Route::get('/sede', 'SedeController@index');
Route::get('/sede/create', 'SedeController@getCreate');
Route::get('/sede/delete/{id}', 'SedeController@getDelete');
Route::get('/sede/edit/{id}', 'SedeController@getEdit');
Route::post('/sede/create', 'SedeController@postCreate');
Route::post('/sede/edit', 'SedeController@postedit');










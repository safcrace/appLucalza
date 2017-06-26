<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', [
    'uses' => 'HomeController@index',
    'as'   => 'home'
]);


// Usuarios routes...
Route::get('usuario/anular/{id}', ['uses' => 'UsuarioController@anular', 'as' => 'anularUsuario']);
Route::get('empresa/usuario/{id}', ['uses' => 'UsuarioController@indexUsuario', 'as' => 'indexUsuario']);
Route::resource('usuarios', 'UsuarioController');

// Authentication routes...
Route::get('login', [
    'uses' => 'Auth\AuthController@getLogin',
    'as'   => 'login'
]);
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', [
     'uses' => 'Auth\AuthController@getLogout',
     'as' => 'logout'
]);
// Registration routes...
Route::get('register', [
    'uses' => 'Auth\AuthController@getRegister',
    'as'   => 'register'
]);
Route::post('register', 'Auth\AuthController@postRegister');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

// Monedas routes...
Route::get('moneda/anular/{id}', ['uses' => 'MonedaController@anular', 'as' => 'anular']);
Route::resource('monedas', 'MonedaController');


// Tasa Cambio routes...
Route::get('tasaCambio/moneda/{id}', ['uses' => 'TasaCambioController@createTasaCambio', 'as' => 'createTasa']);
Route::resource('tasaCambio', 'TasaCambioController');

// Empresas routes...
Route::get('empresa/{id}/create/usuario',  ['uses' => 'UsuarioController@empresaCreateUsuario', 'as' => 'createUsuario']);
Route::get('empresa/anular/{id}', ['uses' => 'EmpresaController@anular', 'as' => 'anularEmpresa']);
Route::resource('empresas', 'EmpresaController');
Route::get('usuarioEmpresa/{id}', ['uses' => 'UsuarioController@usuarioEmpresa', 'as' => 'usuarioEmpresa']);

// Rutas routes
Route::get('rutas/usuario/{id}', ['uses' => 'RutaController@indexRutasUsuario', 'as' => 'indexRutasUsuario']);
Route::get('ruta/anular/{id}', ['uses' => 'RutaController@anular', 'as' => 'anularRuta']);
Route::get('empresa/{id}/create/ruta/usuario',  ['uses' => 'RutaController@empresaCreateRutaUsuario', 'as' => 'createRutaUsuario']);
Route::get('empresa/{id}/create/ruta',  ['uses' => 'RutaController@empresaCreateRuta', 'as' => 'createRuta']);
Route::get('empresa/ruta/{id}', ['uses' => 'RutaController@indexRuta', 'as' => 'indexRuta']);
Route::post('empresa/usuario/ruta', ['uses' => 'RutaController@storeUsuarioRuta', 'as' => 'storeUsuarioRuta']);
Route::get('edit/usuario/ruta/{id}', ['uses' => 'RutaController@UsuarioRutaEdit', 'as' => 'editUsuarioRuta']);
Route::patch('update/usuario/ruta/{id}', ['uses' => 'RutaController@updateUsuarioRuta', 'as' => 'updateUsuarioRuta']);
Route::resource('rutas', 'RutaController');

// Proveedores routes
Route::get('proveedor/anular/{id}', ['uses' => 'ProveedorController@anular', 'as' => 'anularProveedor']);
Route::get('empresa/{id}/create/proveedor',  ['uses' => 'ProveedorController@empresaCreateProveedor', 'as' => 'createProveedor']);
Route::get('empresa/proveedor/{id}', ['uses' => 'ProveedorController@indexProveedor', 'as' => 'indexProveedor']);
Route::resource('proveedores', 'ProveedorController');

// Tipo Gasto routes
Route::get('tipoGasto/anular/{id}', ['uses' => 'TipoGastoController@anular', 'as' => 'anularTipoGasto']);
Route::get('empresa/{id}/create/tipoGasto',  ['uses' => 'tipoGastoController@empresaCreateTipoGasto', 'as' => 'createTipoGasto']);
Route::get('empresa/tipoGasto/{id}', ['uses' => 'TipoGastoController@indexTipoGasto', 'as' => 'indexTipoGasto']);
Route::resource('tipoGastos', 'TipoGastoController');

// Presupuesto routes
Route::get('presupuesto/create/{id}', ['uses' => 'PresupuestoController@presupuestoCreate', 'as' => 'presupuestoCreate']);
Route::resource('presupuestos', 'PresupuestoController');

// Detalle Presupuestos routes
Route::get('presupuesto/{id}/create/detalle',  ['uses' => 'DetallePresupuestoController@presupuestoCreateDetalle', 'as' => 'createDetalle']);
Route::resource('detallePresupuestos', 'DetallePresupuestoController');

// Liquidacion routes
//Route::get('presupuesto/create/{id}', ['uses' => 'PresupuestoController@presupuestoCreate', 'as' => 'presupuestoCreate']);
Route::resource('liquidaciones', 'LiquidacionController');

// Detalle Presupuestos routes
Route::get('liquidacion/{id}/create/factura',  ['uses' => 'FacturaController@liquidacionCreateFactura', 'as' => 'createFactura']);
Route::resource('facturas', 'FacturaController');

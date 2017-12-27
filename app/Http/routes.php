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
Route::get('empresa/vendedores/supervisor/{id}', ['uses' => 'UsuarioController@vendedoresSupervisor', 'as' => 'vendedoresSupervisor']);
Route::post('empresa/usuarios/crea/equipo', ['uses' => 'UsuarioController@creaEquipo', 'as' => 'creaEquipo']);
Route::get('empresa/usuarios/{id}/equipo', ['uses' => 'UsuarioController@asignaEquipo', 'as' => 'asignaEquipo']);
Route::get('usuario/anular/{id}', ['uses' => 'UsuarioController@anular', 'as' => 'anularUsuario']);
Route::get('usuario/empresa/{id?}', ['uses' => 'UsuarioController@createUsuarioEmpresa', 'as' => 'createUsuarioEmpresa']);
Route::post('usuario/empresa', ['uses' => 'UsuarioController@storeUsuarioEmpresa', 'as' => 'storeUsuarioEmpresa']);
Route::get('usuario/{id}/asignacion/empresa', ['uses' => 'UsuarioController@usuariosAsignadosEmpresa', 'as' => 'usuariosEmpresa']);
Route::get('empresa/usuarioEmpresa/anular/{id}', ['uses' => 'UsuarioController@anularUsuarioEmpresa', 'as' => 'anularUsuarioEmpresa']);
Route::get('equipos/vendedorSupervisor/anular/{id}', ['uses' => 'UsuarioController@anularVendedorSupervisor', 'as' => 'anularVendedorSupervisor']);
Route::get('empresa/usuarios/{id}', ['uses' => 'UsuarioController@indexEmpresas', 'as' => 'indexEmpresas']);
Route::get('usuario/empresas/{id}', ['uses' => 'UsuarioController@getEmpresas', 'as' => 'getEmpresas']);
//Route::get('empresa/usuario/{id}', ['uses' => 'UsuarioController@indexUsuario', 'as' => 'indexUsuario']);
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

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

// Monedas routes...
Route::post('moneda/tasaCambio/cargaArchivo/{id}', ['uses' => 'MonedaController@cargaArchivo', 'as' => 'cargaArchivo']);
Route::get('moneda/anular/{id}', ['uses' => 'MonedaController@anular', 'as' => 'anular']);
Route::resource('monedas', 'MonedaController');


// Tasa Cambio routes...
Route::get('tasaCambio/moneda/{id}', ['uses' => 'TasaCambioController@createTasaCambio', 'as' => 'createTasa']);
Route::get('tasaCambio/anular/{id}', ['uses' => 'TasaCambioController@anularTasaCambio', 'as' => 'anularTasaCambio']);
Route::resource('tasaCambio', 'TasaCambioController');

// Empresas routes...
Route::get('empresa/{id}/create/usuario',  ['uses' => 'UsuarioController@empresaCreateUsuario', 'as' => 'createUsuario']);
Route::get('empresa/anular/{id}', ['uses' => 'EmpresaController@anular', 'as' => 'anularEmpresa']);
Route::resource('empresas', 'EmpresaController');
Route::get('usuarioEmpresa/{id}', ['uses' => 'UsuarioController@usuarioEmpresa', 'as' => 'usuarioEmpresa']);

// Rutas routes
Route::get('rutas/usuario/{id}', ['uses' => 'RutaController@indexRutasUsuario', 'as' => 'indexRutasUsuario']);
Route::get('ruta/anular/{id}', ['uses' => 'RutaController@anular', 'as' => 'anularRuta']);
Route::get('ruta/usuario/anular/{id}', ['uses' => 'RutaController@anularRutaUsuario', 'as' => 'anularRutaUsuario']);
Route::get('empresa/{id}/create/ruta/usuario',  ['uses' => 'RutaController@empresaCreateRutaUsuario', 'as' => 'createRutaUsuario']);
Route::get('empresa/{id}/create/ruta',  ['uses' => 'RutaController@empresaCreateRuta', 'as' => 'createRuta']);
Route::get('empresa/ruta/{id}', ['uses' => 'RutaController@indexRuta', 'as' => 'indexRuta']);
Route::post('empresa/usuario/ruta/{id}', ['uses' => 'RutaController@storeUsuarioRuta', 'as' => 'storeUsuarioRuta']);
Route::get('edit/usuario/ruta/{id}', ['uses' => 'RutaController@UsuarioRutaEdit', 'as' => 'editUsuarioRuta']);
Route::patch('update/usuario/ruta/{id}', ['uses' => 'RutaController@updateUsuarioRuta', 'as' => 'updateUsuarioRuta']);
Route::get('rutas/presupuesto/{id}', ['uses' => 'RutaController@rutasPresupuestadas', 'as' => 'rutasPresupuestadas']);
Route::resource('rutas', 'RutaController');

// Proveedores routes
Route::get('proveedor/anular/{id}', ['uses' => 'ProveedorController@anular', 'as' => 'anularProveedor']);
Route::get('empresa/{id}/create/proveedor',  ['uses' => 'ProveedorController@empresaCreateProveedor', 'as' => 'createProveedor']);
Route::get('empresa/proveedor/{id}', ['uses' => 'ProveedorController@indexProveedor', 'as' => 'indexProveedor']);
Route::get('tipoProveedor/actualizar/{id}', ['uses' => 'ProveedorController@actualizarProveedor', 'as' => 'actualizarProveedor']);
Route::resource('proveedores', 'ProveedorController');

// Tipo Gasto routes
Route::get('tipoGasto/anular/{id}', ['uses' => 'TipoGastoController@anular', 'as' => 'anularTipoGasto']);
Route::get('empresa/{id}/create/tipoGasto',  ['uses' => 'tipoGastoController@empresaCreateTipoGasto', 'as' => 'createTipoGasto']);
Route::get('empresa/tipoGasto/{id}', ['uses' => 'TipoGastoController@indexTipoGasto', 'as' => 'indexTipoGasto']);
Route::get('tipoGasto/{id}', ['uses' => 'TipoGastoController@getTipoGasto', 'as' => 'getTipoGasto']);
Route::get('tipoGasto/subcategoria/{id}', ['uses' => 'TipoGastoController@getSubcategoriaTipoGasto', 'as' => 'getSubcategoriaTipoGasto']);
Route::resource('tipoGastos', 'TipoGastoController');

// Subcategoria Tipo Gasto routes...
Route::get('tipoGasto/subcategoriaTipoGasto/{id}', ['uses' => 'SubcategoriaTipoGastoController@createSubcategoria', 'as' => 'createSubcategoria']);
Route::get('subcategoriaTipoGasto/anular/{id}', ['uses' => 'SubcategoriaTipoGastoController@anularSubcategoriaTipoGasto', 'as' => 'anularSubcategoriaTipoGasto']);
Route::resource('subcategoriaTipoGastos', 'SubcategoriaTipoGastoController');

// Presupuesto routes
Route::get('presupuesto/anular/{id}', ['uses' => 'PresupuestoController@anular', 'as' => 'anularPresupuesto']);
Route::get('presupuesto/create/{id}', ['uses' => 'PresupuestoController@presupuestoCreate', 'as' => 'presupuestoCreate']);
Route::get('presupuesto/otrosgastos', ['uses' => 'PresupuestoController@indexOtrosGastos', 'as' => 'indexPresupuestoOtrosGastos']);
Route::resource('presupuestos', 'PresupuestoController');

// Detalle Presupuestos routes
Route::get('presupuesto/{id}/create/detalle',  ['uses' => 'DetallePresupuestoController@presupuestoCreateDetalle', 'as' => 'createDetalle']);
Route::get('presupuesto/detalle/anular/{id}', ['uses' => 'DetallePresupuestoController@anular', 'as' => 'anularDetallePresupuesto']);
Route::resource('detallePresupuestos', 'DetallePresupuestoController');

// Liquidacion routes
//Route::get('presupuesto/create/{id}', ['uses' => 'PresupuestoController@presupuestoCreate', 'as' => 'presupuestoCreate']);
Route::get('liquidacion/{id}/update/enviar', ['uses' => 'LiquidacionController@updateEnviarLiquidacion', 'as' => 'enviarLiquidacion']);
Route::patch('liquidacion/{id}/update/supervisor', ['uses' => 'LiquidacionController@updateLiquidacionCorreccion', 'as' => 'correccionLiquidacion']);
Route::patch('liquidacion/{id}/update/aprobacion', ['uses' => 'LiquidacionController@updateLiquidacionAprobacion', 'as' => 'aprobacionLiquidacion']);
Route::patch('liquidacion/{id}/update/contabilidad', ['uses' => 'LiquidacionController@updateLiquidacionCorreccionContabilidad', 'as' => 'correccionLiquidacionContabilidad']);
Route::get('liquidacion/anular/{id}', ['uses' => 'LiquidacionController@anular', 'as' => 'anularLiquidacion']);
//Route::get('liquidacion/enviar/{id}', ['uses' => 'LiquidacionController@show', 'as' => 'enviarLiquidacion']);
Route::get('liquidacion/create/{id}', ['uses' => 'LiquidacionController@liquidacionCreate', 'as' => 'liquidacionCreate']);
Route::get('liquidaciones/tipo/{id}', ['uses' => 'LiquidacionController@IndexGeneral', 'as' => 'indexGeneral']);
Route::resource('liquidaciones', 'LiquidacionController');

// Facturas routes
Route::get('liquidacion/{id}/create/factura',  ['uses' => 'FacturaController@liquidacionCreateFactura', 'as' => 'createFactura']);
Route::patch('update/factura/{id}/supervisor/comentario', ['uses' => 'FacturaController@updateComentarioFactura', 'as' => 'comentarioSupervisor']);
Route::patch('update/factura/{id}/contabilidad/comentario', ['uses' => 'FacturaController@updateComentarioContabilidadFactura', 'as' => 'comentarioContabilidad']);
Route::get('factura/tipoGasto/{id}', ['uses' => 'FacturaController@tipoGasto', 'as' => 'tipoGasto']);
Route::get('factura/anular/{id}', ['uses' => 'FacturaController@anular', 'as' => 'anularFactura']);
Route::post('factura/imagen/reemplazar', ['uses' => 'FacturaController@sustituirFactura', 'as' => 'sustituirFactura']);
Route::resource('facturas', 'FacturaController');

//Supervisor routes

Route::get('supervisor', ['uses' => 'SupervisorController@index', 'as' => 'supervisor']);
Route::get('supervisor/show/{id}', ['uses' => 'SupervisorController@show', 'as' => 'showLiquidacion']);

//Contabilidad routes
Route::get('contabilidad/reporte/{id}', ['uses' => 'ContabilidadController@report', 'as' => 'reporteContabilidad']);
Route::get('contabilidad', ['uses' => 'ContabilidadController@index', 'as' => 'contabilidad']);
Route::get('contabilidad/show/{id}', ['uses' => 'ContabilidadController@show', 'as' => 'showLiquidacionRev']);
Route::get('contabilidad/sap/{id}', ['uses' => 'ContabilidadController@envioSap', 'as' => 'envioSap']);

//Permisos routes
Route::get('permisos/role', ['uses' => 'PermisoController@asignaPermisosRole', 'as' => 'asignaPermisosRole']);
Route::get('permisos/usuario', ['uses' => 'PermisoController@asignaPermisosUsuario', 'as' => 'asignaPermisosUsuario']);
Route::get('permisos/usuario/{id}', ['uses' => 'PermisoController@showPermisos', 'as' => 'showPermisos']);
Route::post('permisos/role/create', ['uses' => 'PermisoController@creaPermisoRole', 'as' => 'creaPermisosRole']);
Route::post('permisos/usuario/create', ['uses' => 'PermisoController@creaPermisoUsuario', 'as' => 'creaPermisosUsuario']);
Route::get('permiso/anular/{id}', ['uses' => 'PermisoController@anular', 'as' => 'anularPermiso']);
Route::resource('permisos', 'PermisoController');

//Roles routes
Route::get('roles/usuario', ['uses' => 'RoleController@asignaRoleUsuario', 'as' => 'asignaRoleUsuario']);
Route::post('roles/usuario/create', ['uses' => 'RoleController@creaRolesUsuario', 'as' => 'creaRolesUsuario']);
Route::get('role/anular/{id}', ['uses' => 'RoleController@anular', 'as' => 'anularRole']);
Route::resource('roles', 'RoleController');

//Web Service routes
Route::get('webservice/codigo/proveedorSap/{id}', ['uses' => 'WebServiceController@getCodeProveedorSap', 'as' => 'codigoProveedorSap']);
Route::get('webservice/cuentaContable/exenta/{id}', ['uses' => 'WebServiceController@getCuentaContableExenta', 'as' => 'cuentaContableExenta']);
Route::get('webservice/cuentaContable/afecta/{id}', ['uses' => 'WebServiceController@getCuentaContableAfecta', 'as' => 'cuentaContableAfecta']);
Route::get('webservice/cuentaContable/remanente/{id}', ['uses' => 'WebServiceController@getCuentaContableRemanente', 'as' => 'cuentaContableRemanente']);
Route::get('webservice/codigoImpuesto/exento/{id}', ['uses' => 'WebServiceController@getCodigoImpuestoExento', 'as' => 'codigoImpuestoExento']);
Route::get('webservice/codigoImpuesto/afecto/{id}', ['uses' => 'WebServiceController@getCodigoImpuestoAfecto', 'as' => 'codigoImpuestoAfecto']);
Route::get('webservice/codigoImpuesto/remanente/{id}', ['uses' => 'WebServiceController@getCodigoImpuestoRemanente', 'as' => 'codigoImpuestoRemanente']);
Route::get('webservice/codigo/centroCosto1/{id}', ['uses' => 'WebServiceController@getCodigoCentroCostoUno', 'as' => 'codigoCentroCostoUno']);
Route::get('webservice/codigo/centroCosto2/{id}', ['uses' => 'WebServiceController@getCodigoCentroCostoDos', 'as' => 'codigoCentroCostoDos']);
Route::get('webservice/codigo/centroCosto3/{id}', ['uses' => 'WebServiceController@getCodigoCentroCostoTres', 'as' => 'codigoCentroCostoTres']);
Route::get('webservice/codigo/centroCosto4/{id}', ['uses' => 'WebServiceController@getCodigoCentroCostoCuatro', 'as' => 'codigoCentroCostoCuatro']);
Route::get('webservice/codigo/centroCosto5/{id}', ['uses' => 'WebServiceController@getCodigoCentroCostoCinco', 'as' => 'codigoCentroCostoCinco']);
Route::get('webservice/tasaCambio/{id}', ['uses' => 'WebServiceController@getTasaCambio', 'as' => 'tasaCambio']);

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App\Empresa;
use App\Ruta;
use App\Presupuesto;
use App\UsuarioRuta;
use App\DetallePresupuesto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class PresupuestoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Carbon
     */
    public function __construct()
    {
      Carbon::setLocale('es');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$usuario_id = Auth::user()->id;
        //dd($usuario_id);
        $empresa_id = Session::get('empresa');

        $presupuestos = Presupuesto::select('pre_presupuesto.ID as ID', 'users.nombre as USUARIO', 'cat_ruta.DESCRIPCION as RUTA', 'pre_presupuesto.VIGENCIA_INICIO', 'pre_presupuesto.VIGENCIA_FINAL')
                                      ->orderBy('pre_presupuesto.ID')
                                      ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'pre_presupuesto.USUARIORUTA_ID')
                                      ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                      ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                      ->join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id' )
                                      ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
                                      ->where('pre_presupuesto.ANULADO', '=', 0)
                                      ->where('cat_empresa.ID', '=', $empresa_id)
                                      ->paginate(10);

        /*"select p.ID, u.nombre, r.DESCRIPCION, e.DESCRIPCION
 from pre_presupuesto as p inner join cat_usuarioruta as ur on ur.ID = p.USUARIORUTA_ID
                           inner join users as u on u.id = ur.USER_ID
                           inner join cat_ruta as r on r.ID = ur.RUTA_ID
                           inner join cat_usuarioempresa as ue on ue.USER_ID = u.id
                           inner join cat_empresa as e on e.ID = ue.EMPRESA_ID
                           where (e.ID = 1) and (e.ANULADO = 0)"*/
//dd($presupuestos);

        return view('presupuestos.index', compact( 'presupuestos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function presupuestoCreate($id)
    {
        $empresa_id = Session::get('empresa');

        $moneda = Empresa::select('cat_moneda.ID', 'cat_moneda.DESCRIPCION')
                            ->join('cat_moneda', 'cat_moneda.ID', '=', 'cat_empresa.MONEDA_ID')
                            ->where('cat_empresa.ID', '=',  $empresa_id)
                            ->first();

        $usuarios = User::join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
                              ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
                              ->where('cat_usuarioempresa.EMPRESA_ID', '=', $empresa_id)
                              ->lists('users.nombre', 'users.id')
                              ->toArray();

        $rutas = Ruta::join('cat_usuarioruta', 'cat_usuarioruta.RUTA_ID', '=', 'cat_ruta.ID')
                              ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                              ->lists('cat_ruta.DESCRIPCION', 'cat_ruta.ID')
                              ->toArray();

        /*$combos = Presupuesto::select('pre_presupuesto.ID as ID', 'users.id as USUARIO', 'cat_ruta.ID as RUTA', 'cat_empresa.ID as EMPRESA')
                                      ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'pre_presupuesto.USUARIORUTA_ID')
                                      ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                      ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                      ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_ruta.EMPRESA_ID')
                                      ->where('pre_presupuesto.ID', '=', $id)
                                      ->first();*/
        $vigenciaInicio = null;
        $vigenciaFinal = null;

        return view('presupuestos.create', compact('usuario_id', 'usuarios', 'rutas', 'moneda', 'vigenciaInicio', 'vigenciaFinal'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresa_id = Session::get('empresa');

        $moneda = Empresa::select('cat_moneda.ID', 'cat_moneda.DESCRIPCION')
            ->join('cat_moneda', 'cat_moneda.ID', '=', 'cat_empresa.MONEDA_ID')
            ->where('cat_empresa.ID', '=',  $empresa_id)
            ->first();

        $usuarios = User::join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
            ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
            ->where('cat_usuarioempresa.EMPRESA_ID', '=', $empresa_id)
            ->lists('users.nombre', 'users.id')
            ->toArray();

        $rutas = Ruta::join('cat_usuarioruta', 'cat_usuarioruta.RUTA_ID', '=', 'cat_ruta.ID')
            ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
            ->lists('cat_ruta.DESCRIPCION', 'cat_ruta.ID')
            ->toArray();

        /*$combos = Presupuesto::select('pre_presupuesto.ID as ID', 'users.id as USUARIO', 'cat_ruta.ID as RUTA', 'cat_empresa.ID as EMPRESA')
                                      ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'pre_presupuesto.USUARIORUTA_ID')
                                      ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                      ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                      ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_ruta.EMPRESA_ID')
                                      ->where('pre_presupuesto.ID', '=', $id)
                                      ->first();*/
        $vigenciaInicio = null;
        $vigenciaFinal = null;

        return view('presupuestos.create', compact('usuarios', 'rutas', 'moneda', 'vigenciaInicio', 'vigenciaFinal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usuarioRuta_id = UsuarioRuta::select('ID')
                        ->where('USER_ID', '=', $request->USUARIO_ID)
                        ->where('RUTA_ID', '=', $request->RUTA_ID)
                        ->first();
        //dd($usuarioRuta_id->ID);
        $presupuesto = new Presupuesto();

        $presupuesto->USUARIORUTA_ID = $usuarioRuta_id->ID;
        $presupuesto->MONEDA_ID = $request->MONEDA_ID;

        $presupuesto->VIGENCIA_INICIO = $request->VIGENCIA_INICIO;
        $presupuesto->VIGENCIA_FINAL = $request->VIGENCIA_FINAL;
        $presupuesto->ANULADO = $request->ANULADO;

        $presupuesto->save();

        return redirect::to('presupuestos/' . $presupuesto->id . '/edit')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario_id = $id;
        $empresa_id = Session::get('empresa');

        $usuarios = User::join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
            ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
            ->where('cat_usuarioempresa.EMPRESA_ID', '=', $empresa_id)
            ->lists('users.nombre', 'users.id')
            ->toArray();

        //dd($empresa_id);
        $rutas = User::join('cat_usuarioruta', 'cat_usuarioruta.USER_ID', '=', 'users.id')
            ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
            ->where('cat_usuarioruta.USER_ID', '=', $id)
            ->lists('cat_ruta.DESCRIPCION', 'cat_ruta.ID')
            ->toArray();

        $moneda = Empresa::select('cat_moneda.ID', 'cat_moneda.DESCRIPCION')
            ->join('cat_moneda', 'cat_moneda.ID', '=', 'cat_empresa.MONEDA_ID')
            ->where('cat_empresa.ID', '=',  $empresa_id)
            ->first();

        $vigenciaInicio = null;
        $vigenciaFinal = null;

        return view('presupuestos.create', compact('usuario_id', 'usuarios', 'rutas', 'moneda', 'vigenciaInicio', 'vigenciaFinal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);

        $usuario_id = Auth::user()->id;

        $empresa_id = Session::get('empresa');

        $moneda = Empresa::select('cat_moneda.ID', 'cat_moneda.DESCRIPCION')
            ->join('cat_moneda', 'cat_moneda.ID', '=', 'cat_empresa.MONEDA_ID')
            ->where('cat_empresa.ID', '=',  $empresa_id)
            ->first();


        $usuarios = User::join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
                                ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
                                ->where('cat_usuarioempresa.EMPRESA_ID', '=', $empresa_id)
                                ->lists('users.nombre', 'users.id')
                                ->toArray();

        /*$frecuencia = FrecuenciaTiempo::lists('DESCRIPCION', 'ID')
                                        ->toArray();*/

        $combos = Presupuesto::select('pre_presupuesto.ID as ID', 'users.id as USUARIO', 'cat_ruta.ID as RUTA', 'cat_empresa.ID as EMPRESA')
                                      ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'pre_presupuesto.USUARIORUTA_ID')
                                      ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                      ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                      ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_ruta.EMPRESA_ID')
                                      ->where('pre_presupuesto.ID', '=', $id)
                                      ->first();

        $usuario_id = $combos->USUARIO;

        $ruta_id = $combos->RUTA;
        //dd($ruta_id);
        $vigenciaInicio = $presupuesto->VIGENCIA_INICIO;
        $vigenciaFinal = $presupuesto->VIGENCIA_FINAL;

        $rutas = User::join('cat_usuarioruta', 'cat_usuarioruta.USER_ID', '=', 'users.id')
            ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
            ->where('cat_usuarioruta.USER_ID', '=', $usuario_id)
            ->lists('cat_ruta.DESCRIPCION', 'cat_ruta.ID')
            ->toArray();


        $detallePresupuestos = DetallePresupuesto::select('pre_detpresupuesto.ID', 'cat_tipogasto.DESCRIPCION as TIPOGASTO', 'pre_detpresupuesto.MONTO', 'cat_frecuenciatiempo.DESCRIPCION as FRECUENCIA')
                                                  ->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'pre_detpresupuesto.TIPOGASTO_ID')
                                                  ->join('cat_frecuenciatiempo', 'cat_frecuenciatiempo.ID', '=', 'pre_detpresupuesto.FRECUENCIATIEMPO_ID')
                                                  ->where('pre_detpresupuesto.PRESUPUESTO_ID', '=', $id)
                                                  ->where('pre_detpresupuesto.ANULADO', '=', 0)
                                                  ->paginate();


        /*$detallePresupuesto = DetallePresupuesto::select('pre_detpresupuesto.')

        $tasaCambio = TasaCambio::select('cat_tasacambio.ID', 'cat_tasacambio.MONEDA_ID', 'cat_tasacambio.FECHA', 'cat_tasacambio.COMPRA', 'cat_tasacambio.VENTA', 'cat_tasacambio.PROMEDIO', 'cat_tasacambio.ANULADO')
                                  ->where('cat_tasacambio.MONEDA_ID', '=', $id)->paginate(4);*/

        return view('presupuestos.edit', compact('presupuesto', 'usuarios', 'rutas', 'usuario_id', 'ruta_id', 'detallePresupuestos', 'moneda', 'vigenciaInicio', 'vigenciaFinal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $usuarioRuta_id = UsuarioRuta::select('ID')
                        ->where('USER_ID', '=', $request->USUARIO_ID)
                        ->where('RUTA_ID', '=', $request->RUTA_ID)
                        ->first();

        //$proveedor = Proveedor::findOrFail($id);
        //$moneda->fill($request->all());




        Presupuesto::where('ID', $id)
                ->update(['USUARIORUTA_ID' => $usuarioRuta_id->ID, 'MONEDA_ID' => $request->MONEDA_ID, 'VIGENCIA_INICIO' => $request->VIGENCIA_INICIO,
                          'VIGENCIA_FINAL' => $request->VIGENCIA_FINAL, 'ANULADO' => $request->ANULADO]);

        return Redirect::to('presupuestos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function anular($id)
    {
        Presupuesto::where('ID', $id)
            ->update(['ANULADO' => 1]);

        return 1; //Redirect::to('presupuestos');
    }

}

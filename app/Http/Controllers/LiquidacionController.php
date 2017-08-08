<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Liquidacion;
use App\Ruta;
use App\UsuarioRuta;
use App\Factura;

class LiquidacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario_id = Auth::user()->id;

        $liquidaciones = Liquidacion::select('liq_liquidacion.ID as ID', 'liq_liquidacion.FECHA_INICIO', 'liq_liquidacion.FECHA_FINAL', 'cat_ruta.DESCRIPCION as RUTA', 'cat_estadoliquidacion.DESCRIPCION', 'users.nombre' )
                                      ->orderBy('cat_estadoliquidacion.ID')
                                      ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                      ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                      ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                      ->join('cat_estadoliquidacion', 'cat_estadoliquidacion.ID', '=', 'liq_liquidacion.ESTADOLIQUIDACION_ID')
                                      ->where('liq_liquidacion.ANULADO', '=', 0)
                                      ->where('users.id','=', $usuario_id)
                                      ->whereIn('liq_liquidacion.ESTADOLIQUIDACION_ID', [1,6])
                                      ->paginate();

        return view('liquidaciones.index', compact('usuario_id', 'liquidaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuario = Auth::user()->nombre;
        $usuario_id = Auth::user()->id;

        $rutas = Ruta::join('cat_usuarioruta', 'cat_usuarioruta.RUTA_ID', '=', 'cat_ruta.ID')
                              ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                              ->where('cat_usuarioruta.USER_ID', '=', $usuario_id)
                              ->lists('cat_ruta.DESCRIPCION', 'cat_ruta.ID')
                              ->toArray();
                             ;
        return view('liquidaciones.create', compact('usuario', 'usuario_id', 'rutas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usuarioRuta = UsuarioRuta::select('ID')
                        ->where('USER_ID', '=', $request->USUARIO_ID)
                        ->where('RUTA_ID', '=', $request->RUTA_ID)
                        ->first();

        $liquidacion = new Liquidacion();

        $liquidacion->USUARIORUTA_ID = $usuarioRuta->ID;
        $liquidacion->ESTADOLIQUIDACION_ID = 1;
        $liquidacion->FECHA_INICIO = $request->FECHA_INICIO;
        $liquidacion->FECHA_FINAL = $request->FECHA_FINAL;
        $liquidacion->COMENTARIO_PAGO = $request->COMENTARIO_PAGO;
        $liquidacion->SUPERVISOR_AUTORIZACION = 0;
        $liquidacion->CONTABILIDAD_AUTORIZACION = 0;
        $liquidacion->ANULADO = 0;

        $liquidacion->save();

        return redirect::to('liquidaciones/' . $liquidacion->id . '/edit');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $id;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $liquidacion = Liquidacion::findOrFail($id);

        $usuario = Liquidacion::select('users.nombre' )
                                    ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                    ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                    ->where('liq_liquidacion.ID', '=', $id)
                                    ->first();
        $usuario_id = Auth::user()->id;

        $rutas = Ruta::join('cat_usuarioruta', 'cat_usuarioruta.RUTA_ID', '=', 'cat_ruta.ID')
                              ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                              ->where('users.id', '=', $usuario_id)
                              ->lists('cat_ruta.DESCRIPCION', 'cat_ruta.ID')
                              ->toArray();
//dd($rutas);
        /*$frecuencia = FrecuenciaTiempo::lists('DESCRIPCION', 'ID')
                                        ->toArray();*/

        $combo = Liquidacion::select('liq_liquidacion.ID as ID', 'cat_ruta.ID as RUTA')
                                      ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                      ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                      ->where('liq_liquidacion.ID', '=', $id)
                                      ->first();
//dd($liquidacion->SUPERVISOR_COMENTARIO);
        $facturas = Factura::select('liq_factura.ID', 'cat_proveedor.NOMBRE', 'liq_factura.SERIE as SERIE', 'liq_factura.NUMERO as NUMERO', 'liq_factura.TOTAL as TOTAL',
                                    'liq_factura.FECHA_FACTURA', 'cat_tipogasto.DESCRIPCION as TIPOGASTO', 'liq_factura.COMENTARIO_SUPERVISOR AS RECHAZO')
                                                  ->join('cat_proveedor', 'cat_proveedor.ID', '=', 'liq_factura.PROVEEDOR_ID')
                                                  ->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'liq_factura.TIPOGASTO_ID')
                                                  //->join('cat_frecuenciatiempo', 'cat_frecuenciatiempo.ID', '=', 'pre_detpresupuesto.FRECUENCIATIEMPO_ID')
                                                  ->where('liq_factura.LIQUIDACION_ID', '=', $id)
                                                  ->where('liq_factura.ANULADO', '=', 0)
                                                  ->paginate();

        return view('liquidaciones.edit', compact('liquidacion', 'usuario', 'usuario_id', 'rutas', 'combo', 'facturas'));
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


        Liquidacion::where('ID', $id)
                ->update(['USUARIORUTA_ID' => $usuarioRuta_id->ID, 'FECHA_INICIO' => $request->FECHA_INICIO, 'FECHA_FINAL' => $request->FECHA_FINAL,
                          'COMENTARIO_PAGO' => $request->COMENTARIO_PAGO]);

        return Redirect::to('liquidaciones');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateEnviarLiquidacion(Request $request, $id)
    {
        Liquidacion::where('ID', $id)
            ->update(['ESTADOLIQUIDACION_ID' => 2]);
        return Redirect::to('liquidaciones');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateLiquidacionCorreccion(Request $request, $id)
    {

        Liquidacion::where('ID', $id)
                ->update(['SUPERVISOR_COMENTARIO' => $request->SUPERVISOR_COMENTARIO, 'ESTADOLIQUIDACION_ID' => 6]);

        return Redirect::to('supervisor');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateLiquidacionCorreccionContabilidad(Request $request, $id)
    {
        Liquidacion::where('ID', $id)
                ->update(['CONTABILIDAD_COMENTARIO' => $request->CONTABILIDAD_COMENTARIO, 'ESTADOLIQUIDACION_ID' => 6]);

        return Redirect::to('contabilidad');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateLiquidacionAprobacion(Request $request, $id)
    {

        Liquidacion::where('ID', $id)
                ->update(['ESTADOLIQUIDACION_ID' => 3]);

        return Redirect::to('supervisor');
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
        Liquidacion::where('ID', $id)
            ->update(['ANULADO' => 1]);
        return 1; //Redirect::to('liquidaciones');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Factura;
use App\Liquidacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SupervisorController extends Controller
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
        $empresa_id = Session::get('empresa');

        $usuario_id = Auth::user()->id;

        $liquidaciones = Liquidacion::select('liq_liquidacion.ID', 'liq_liquidacion.FECHA_INICIO', 'liq_liquidacion.FECHA_FINAL','users.nombre as USUARIO', 'cat_ruta.DESCRIPCION as RUTA')
                                  ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                  ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                  ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                  ->join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
                                  ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
                                  ->join('cat_supervisor_vendedor', 'cat_supervisor_vendedor.VENDEDOR_ID_USUARIO', '=', 'users.id')
                                  ->where('cat_supervisor_vendedor.SUPERVISOR_ID_USUARIO', '=', $usuario_id )
                                  ->where('cat_empresa.ID', '=', $empresa_id)
                                  ->where('liq_liquidacion.ESTADOLIQUIDACION_ID', '=', 2)
                                  ->paginate(10);

                                      /*$totales = \DB::select("select SUM(liq_factura.TOTAL)
                              FROM liq_factura inner join liq_liquidacion on liq_liquidacion.ID = liq_factura.LIQUIDACION_ID");
                            //  dd($totales);*/

        return view('supervisor.index', compact('liquidaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $liquidacion = Liquidacion::select('liq_liquidacion.ID', 'liq_liquidacion.FECHA_INICIO', 'users.nombre as USUARIO', 'cat_ruta.DESCRIPCION as RUTA',
                                           'liq_liquidacion.SUPERVISOR_COMENTARIO', 'liq_liquidacion.CONTABILIDAD_COMENTARIO' )
                                  ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                  ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                  ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                  ->where('liq_liquidacion.id', '=', $id)
                                 // ->where('liq_liquidacion.ESTADOLIQUIDACION_ID', '=', 2)
                                  ->first();
//dd($liquidacion);
        $facturas = Factura::select('liq_factura.ID', 'cat_proveedor.NOMBRE', 'liq_factura.SERIE as SERIE', 'liq_factura.NUMERO as NUMERO', 'liq_factura.TOTAL as TOTAL',
                                    'liq_factura.FECHA_FACTURA', 'cat_tipogasto.DESCRIPCION as TIPOGASTO', 'liq_factura.COMENTARIO_SUPERVISOR', 'liq_factura.COMENTARIO_CONTABILIDAD',
                                    'users.email as EMAIL', 'liq_factura.FOTO as FOTO')
                                                  ->join('cat_proveedor', 'cat_proveedor.ID', '=', 'liq_factura.PROVEEDOR_ID')
                                                  ->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'liq_factura.TIPOGASTO_ID')
                                                  ->join('liq_liquidacion', 'liq_liquidacion.ID', '=', 'liq_factura.LIQUIDACION_ID')
                                                  ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                                  ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                                  //->join('cat_frecuenciatiempo', 'cat_frecuenciatiempo.ID', '=', 'pre_detpresupuesto.FRECUENCIATIEMPO_ID')
                                                  ->where('liq_factura.LIQUIDACION_ID', '=', $id)
                                                  ->where('liq_factura.ANULADO', '=', 0)                                                  
                                                  ->paginate();

        $corregirFactura = array();

        return view('supervisor.edit', compact('liquidacion', 'facturas', 'corregirFactura'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
}

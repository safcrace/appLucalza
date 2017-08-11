<?php

namespace App\Http\Controllers;

use App\TipoProveedor;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Liquidacion;
use App\Factura;
use Illuminate\Support\Facades\Session;

class ContabilidadController extends Controller
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

        $liquidaciones = Liquidacion::select('liq_liquidacion.ID', 'liq_liquidacion.FECHA_INICIO', 'liq_liquidacion.FECHA_FINAL', 'users.nombre as USUARIO', 'cat_ruta.DESCRIPCION as RUTA' )
                                  ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                  ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                  ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                  ->join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
                                  ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
                                  //quitar esto ->where('cat_empresa.ID', '=', $empresa_id)
                                  ->where('liq_liquidacion.ESTADOLIQUIDACION_ID', '=', 3)
                                  ->paginate(10);


        return view('contabilidad.index', compact('liquidaciones'));
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
                                           'liq_liquidacion.SUPERVISOR_COMENTARIO', 'liq_liquidacion.CONTABILIDAD_COMENTARIO')
                                  ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                  ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                  ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                  ->where('liq_liquidacion.id', '=', $id)
                                  ->first();

        $facturas = Factura::select('liq_factura.ID', 'cat_proveedor.ID as PROVEEDORID', 'cat_proveedor.NOMBRE', 'liq_factura.SERIE as SERIE', 'liq_factura.NUMERO as NUMERO', 'liq_factura.TOTAL as TOTAL',
                                    'liq_factura.FECHA_FACTURA', 'cat_tipogasto.DESCRIPCION as TIPOGASTO', 'liq_factura.COMENTARIO_SUPERVISOR', 'liq_factura.COMENTARIO_CONTABILIDAD',
                                    'users.email as EMAIL', 'liq_factura.FOTO as FOTO', 'cat_proveedor.TIPOPROVEEDOR_ID')
                                                ->join('cat_proveedor', 'cat_proveedor.ID', '=', 'liq_factura.PROVEEDOR_ID')
                                                ->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'liq_factura.TIPOGASTO_ID')
                                                ->join('liq_liquidacion', 'liq_liquidacion.ID', '=', 'liq_factura.LIQUIDACION_ID')
                                                ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                                ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                                  //->join('cat_frecuenciatiempo', 'cat_frecuenciatiempo.ID', '=', 'pre_detpresupuesto.FRECUENCIATIEMPO_ID')
                                                  ->where('liq_factura.LIQUIDACION_ID', '=', $id)
                                                  ->paginate();
        $corregirFactura = array();

        $tipoProveedor = TipoProveedor::lists('DESCRIPCION', 'ID')->toArray();

        return view('contabilidad.edit', compact('liquidacion', 'facturas', 'corregirFactura', 'tipoProveedor'));
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

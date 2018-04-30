<?php

namespace App\Http\Controllers;

use App\Factura;
use App\Liquidacion;

use App\TipoProveedor;
use App\Http\Requests;
use App\UsuarioEmpresa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class ContabilidadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('roles:superAdmin,contabilidad');
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
                                  ->where('cat_empresa.ID', '=', $empresa_id)
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
                                    'liq_factura.FECHA_FACTURA', 'cat_tipogasto.DESCRIPCION as TIPOGASTO', 'liq_factura.COMENTARIO_SUPERVISOR', 'liq_factura.COMENTARIO_CONTABILIDAD', 'liq_factura.CORRECCION',
                                    'users.email as EMAIL', 'liq_factura.FOTO as FOTO', 'cat_proveedor.TIPOPROVEEDOR_ID')
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

        $tipoProveedor = TipoProveedor::lists('DESCRIPCION', 'ID')->toArray();

        $correlativo = 1;
        foreach ($facturas as $factura):
            $factura->CORRELATIVO  = $correlativo;            
            $correlativo = $correlativo + 1;            
        endforeach;
//dd($factura);
        return view('contabilidad.edit', compact('liquidacion', 'facturas', 'corregirFactura', 'tipoProveedor'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function report($id) 
    {
        $proveedorNoClasificado = Factura::select('cat_proveedor.TIPOPROVEEDOR_ID')
                                        ->join('cat_proveedor', 'cat_proveedor.ID', '=', 'liq_factura.PROVEEDOR_ID')
                                        ->where('liq_factura.LIQUIDACION_ID', '=', $id)
                                        ->where('cat_proveedor.TIPOPROVEEDOR_ID', '=', 1)
                                        ->where('liq_factura.ANULADO', '=', 0)
                                        ->first();
//dd($proveedorNoClasificado);
        if($proveedorNoClasificado <> null) {
            return back()->with('info', 'Tiene pendiente revisar categoría(s) de Proveedor(es)!');
        }
        
        $empresa_id = Session::get('empresa');
        $usuario_id = Auth::user()->id;
        $codigoUsuarioSap = UsuarioEmpresa::select('USERSAP_ID')->where('USER_ID', '=', $usuario_id)->where('EMPRESA_ID', '=', $empresa_id)->first();
        //dd('codigo usuario: ' .  $codigoUsuarioSap->USERSAP_ID);
        $codigoUsuarioSap = $codigoUsuarioSap->USERSAP_ID;
        //dd($codigoUsuarioSap);

        $liquidacion = Liquidacion::select('liq_liquidacion.ID', 'liq_liquidacion.FECHA_INICIO', 'users.nombre as USUARIO', 
                                           'cat_usuarioempresa.CODIGO_PROVEEDOR_SAP', 'cat_ruta.DESCRIPCION as RUTA', 'liq_liquidacion.FECHA_FINAL')
                                        ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                        ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                        ->join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.ID')
                                        ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                        ->where('liq_liquidacion.id', '=', $id)
                                        ->where('cat_usuarioempresa.EMPRESA_ID', '=', $empresa_id)
                                        ->first();

                                        //dd($liquidacion);

        $facturas = Factura::select('liq_factura.ID', 'cat_proveedor.ID as PROVEEDORID', 'cat_proveedor.NOMBRE', 'cat_proveedor.IDENTIFICADOR_TRIBUTARIO', 'liq_factura.SERIE as SERIE',
                                    'liq_factura.NUMERO as NUMERO', 'liq_factura.TOTAL as TOTAL', 'liq_factura.FECHA_FACTURA', 'liq_factura.MONTO_IVA',
                                    'cat_tipogasto.DESCRIPCION as TIPOGASTO', 'cat_tipogasto.GRUPOTIPOGASTO_ID', 'liq_factura.APROBACION_PAGO', 'cat_tipodocumento.DESCRIPCION as DOCUMENTO',
                                    'users.email as EMAIL', 'liq_factura.FOTO as FOTO', 'cat_proveedor.TIPOPROVEEDOR_ID', 'liq_factura.MONTO_AFECTO',
                                    'MONTO_EXENTO', 'liq_factura.MONTO_REMANENTE', 'pre_detpresupuesto.CENTROCOSTO1', 'pre_detpresupuesto.CENTROCOSTO2',
                                    'pre_detpresupuesto.CENTROCOSTO3', 'pre_detpresupuesto.CENTROCOSTO4', 'pre_detpresupuesto.CENTROCOSTO5', 'cat_tipogasto.CUENTA_CONTABLE_EXENTO', 'cat_tipogasto.CODIGO_IMPUESTO_EXENTO',
                                    'cat_tipogasto.CUENTA_CONTABLE_AFECTO', 'cat_tipogasto.CODIGO_IMPUESTO_AFECTO', 'cat_tipogasto.CUENTA_CONTABLE_REMANENTE', 'cat_tipogasto.CODIGO_IMPUESTO_REMANENTE')
                                        ->join('cat_proveedor', 'cat_proveedor.ID', '=', 'liq_factura.PROVEEDOR_ID')
                                        ->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'liq_factura.TIPOGASTO_ID')
                                        ->join('liq_liquidacion', 'liq_liquidacion.ID', '=', 'liq_factura.LIQUIDACION_ID')
                                        ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                        ->join('cat_tipodocumento', 'cat_tipodocumento.ID', '=', 'liq_factura.TIPODOCUMENTO_ID')
                                        ->join('pre_detpresupuesto', 'pre_detpresupuesto.ID', '=', 'liq_factura.DETPRESUPUESTO_ID')
                                        ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                        //->join('cat_frecuenciatiempo', 'cat_frecuenciatiempo.ID', '=', 'pre_detpresupuesto.FRECUENCIATIEMPO_ID')
                                        ->where('liq_factura.LIQUIDACION_ID', '=', $id)
                                        ->where('liq_factura.ANULADO', '=', 0) 
                                        ->paginate();                                               

        return view('contabilidad.report', compact('liquidacion', 'facturas', 'codigoUsuarioSap'));
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
    public function envioSap($id)
    {        
        /*Liquidacion::where('ID', $id)
        ->update(['ESTADOLIQUIDACION_ID' => 4]);*/

        return Redirect::to('contabilidad');
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

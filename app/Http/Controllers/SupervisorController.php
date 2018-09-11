<?php

namespace App\Http\Controllers;

use App\Factura;

use App\Liquidacion;
use App\Presupuesto;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\SubcategoriaTipoGasto;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SupervisorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('roles:superAdmin,supervisor');
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
        $liquidacion = Liquidacion::select('liq_liquidacion.ID', 'liq_liquidacion.FECHA_INICIO', 'liq_liquidacion.FECHA_FINAL', 'users.nombre as USUARIO', 'cat_ruta.DESCRIPCION as RUTA',
                                           'liq_liquidacion.SUPERVISOR_COMENTARIO', 'liq_liquidacion.CONTABILIDAD_COMENTARIO', 'liq_liquidacion.USUARIORUTA_ID', 'cat_ruta.TIPO_GASTO' )
                                  ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                  ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                  ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                  ->where('liq_liquidacion.id', '=', $id)
                                 // ->where('liq_liquidacion.ESTADOLIQUIDACION_ID', '=', 2)
                                  ->first();

        $facturas = Factura::select('liq_factura.ID', 'cat_proveedor.NOMBRE', 'liq_factura.SERIE as SERIE', 'liq_factura.NUMERO as NUMERO', 'liq_factura.TOTAL as TOTAL',
                                    'liq_factura.FECHA_FACTURA', 'cat_tipogasto.DESCRIPCION as TIPOGASTO', 'liq_factura.COMENTARIO_SUPERVISOR', 'liq_factura.COMENTARIO_CONTABILIDAD',
                                    'users.email as EMAIL', 'liq_factura.FOTO as FOTO', 'liq_factura.CORRECCION', 'liq_factura.MONTO_REMANENTE')
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

        $correlativo = 1;
        foreach ($facturas as $factura):
            $factura->CORRELATIVO  = $correlativo;            
            $correlativo = $correlativo + 1;            
        endforeach;

        $fechaFinal = $liquidacion->FECHA_FINAL;//->format('Y-m-d');
        $fechaInicio = $liquidacion->FECHA_INICIO;//->format('Y-m-d');
       // dd($fechaInicio);
 
         $presupuestoAsignado = Presupuesto::select('pre_detpresupuesto.PRESUPUESTO_ID', 'cat_tipogasto.DESCRIPCION AS TIPOGASTO', 
                                                    'pre_detpresupuesto.MONTO', 'cat_asignacionpresupuesto.DESCRIPCION', 'cat_frecuenciatiempo.DESCRIPCION AS FRECUENCIA')
                                             ->join('pre_detpresupuesto', 'pre_detpresupuesto.PRESUPUESTO_ID', '=', 'pre_presupuesto.ID')
                                             ->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'pre_detpresupuesto.TIPOGASTO_ID')
                                             ->join('cat_asignacionpresupuesto', 'cat_asignacionpresupuesto.ID', '=', 'pre_detpresupuesto.TIPOASIGNACION_ID')
                                             ->join('cat_frecuenciatiempo', 'cat_frecuenciatiempo.ID', '=', 'pre_detpresupuesto.FRECUENCIATIEMPO_ID')
                                             ->where('pre_presupuesto.VIGENCIA_INICIO', '<=', $fechaFinal)
                                             ->where('pre_presupuesto.VIGENCIA_FINAL', '>=', $fechaInicio)
                                             ->where('pre_presupuesto.USUARIORUTA_ID', '=', $liquidacion->USUARIORUTA_ID)
                                             ->get();

        $asignacionMensual = Presupuesto::where('VIGENCIA_INICIO', '<=', $fechaFinal)
                                            ->where('VIGENCIA_FINAL', '>=', $fechaInicio)
                                            ->where('USUARIORUTA_ID', '=', $liquidacion->USUARIORUTA_ID)
                                            ->pluck('ASIGNACION_MENSUAL');
                                           
        if($asignacionMensual > 0) {
            $presupuestoDepreciacion = collect(['TIPOGASTO' => 'Depreciación', 'MONTO' => $asignacionMensual, 'DESCRIPCION' => 'Efectivo', 'FRECUENCIA' => 'Mensual']);
            $presupuestoDepreciacion = $presupuestoDepreciacion->toArray();              
        }

        $empresa_id = Session::get('empresa');

        $unidadMedida = SubcategoriaTipoGasto::join('cat_unidadmedida', 'cat_unidadmedida.ID', '=', 'cat_subcategoria_tipogasto.UNIDAD_MEDIDA_ID')
                                                ->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'cat_subcategoria_tipogasto.TIPOGASTO_ID')
                                                ->where('cat_tipogasto.GRUPOTIPOGASTO_ID', '=', 'BC')                                                
                                                ->where('cat_tipogasto.EMPRESA_ID', '=', $empresa_id)
                                                ->select('cat_unidadmedida.DESCRIPCION')
                                                ->first();

        foreach ($presupuestoAsignado as $presupuesto) {
            if (strtolower($presupuesto->DESCRIPCION) != 'dinero') {
                $presupuesto->DESCRIPCION = $unidadMedida->DESCRIPCION;# code...            
            }
        }

         $noAplicaPago = Factura::where('LIQUIDACION_ID', '=', $liquidacion->ID)->where('ANULADO', '=', 0)->sum('MONTO_REMANENTE');
                                             
         $total = Factura::where('LIQUIDACION_ID', '=', $liquidacion->ID)->where('ANULADO', '=', 0)->sum('TOTAL');  

        return view('supervisor.edit', compact('liquidacion', 'facturas', 'corregirFactura', 'presupuestoAsignado', 'noAplicaPago', 'total', 'presupuestoDepreciacion'));
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

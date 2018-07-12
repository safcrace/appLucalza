<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Factura;
use App\Liquidacion;

use App\Presupuesto;
use App\Http\Requests;
use App\TipoProveedor;
use App\UsuarioEmpresa;
use Illuminate\Http\Request;
use App\SubcategoriaTipoGasto;
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
        $liquidacion = Liquidacion::select('liq_liquidacion.ID', 'liq_liquidacion.FECHA_INICIO', 'liq_liquidacion.FECHA_FINAL', 'users.nombre as USUARIO', 'cat_ruta.DESCRIPCION as RUTA', 'liq_liquidacion.USUARIORUTA_ID',
                                           'liq_liquidacion.SUPERVISOR_COMENTARIO', 'liq_liquidacion.CONTABILIDAD_COMENTARIO')
                                  ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                  ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                  ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                  ->where('liq_liquidacion.id', '=', $id)
                                  ->first();

        $facturas = Factura::select('liq_factura.ID', 'cat_proveedor.ID as PROVEEDORID', 'cat_proveedor.NOMBRE', 'liq_factura.SERIE as SERIE', 'liq_factura.NUMERO as NUMERO', 'liq_factura.TOTAL as TOTAL',
                                    'liq_factura.FECHA_FACTURA', 'cat_tipogasto.DESCRIPCION as TIPOGASTO', 'liq_factura.COMENTARIO_SUPERVISOR', 'liq_factura.COMENTARIO_CONTABILIDAD', 'liq_factura.CORRECCION',
                                    'users.email as EMAIL', 'liq_factura.FOTO as FOTO', 'cat_proveedor.TIPOPROVEEDOR_ID', 'liq_factura.MONTO_REMANENTE', 'liq_factura.ANULARENVIO_SAP')
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

        $fechaFinal = $liquidacion->FECHA_FINAL;//->format('Y-m-d');
        $fechaInicio = $liquidacion->FECHA_INICIO;//->format('Y-m-d');
       // dd($fechaInicio);
 
         $presupuestoAsignado = Presupuesto::select('pre_detpresupuesto.PRESUPUESTO_ID', 'cat_tipogasto.DESCRIPCION AS TIPOGASTO', 
                                                    'pre_detpresupuesto.MONTO', 'cat_asignacionpresupuesto.DESCRIPCION', 'cat_frecuenciatiempo.DESCRIPCION AS FRECUENCIA')
                                             ->join('pre_detpresupuesto', 'pre_detpresupuesto.PRESUPUESTO_ID', '=', 'pre_presupuesto.ID')
                                             ->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'pre_detpresupuesto.TIPOGASTO_ID')
                                             ->join('cat_asignacionpresupuesto', 'cat_asigNacionpresupuesto.ID', '=', 'pre_detpresupuesto.TIPOASIGNACION_ID')
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

         $unidadMedida = SubcategoriaTipoGasto::join('cat_unidadmedida', 'cat_unidadmedida.ID', '=', 'cat_subcategoria_tipogasto.UNIDAD_MEDIDA_ID')
                                                ->where('cat_subcategoria_tipogasto.TIPOGASTO_ID', '=', 3)                                                
                                                ->select('cat_unidadmedida.DESCRIPCION')
                                                ->first();

        foreach ($presupuestoAsignado as $presupuesto) {
            if (strtolower($presupuesto->DESCRIPCION) != 'dinero') {
                $presupuesto->DESCRIPCION = $unidadMedida->DESCRIPCION;# code...            
            }
        }
 
         $noAplicaPago = Factura::where('LIQUIDACION_ID', '=', $liquidacion->ID)->where('ANULADO', '=', 0)->sum('MONTO_REMANENTE');
                                             
         $total = Factura::where('LIQUIDACION_ID', '=', $liquidacion->ID)->where('ANULADO', '=', 0)->sum('TOTAL'); 
//dd($factura);
        return view('contabilidad.edit', compact('liquidacion', 'facturas', 'corregirFactura', 'tipoProveedor', 'presupuestoAsignado', 'noAplicaPago', 'total', 'presupuestoDepreciacion'));
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
                                        ->get();  
                                        
                                        $empresa_id = Session::get('empresa');
/*
        $notaCredito = Empresa::where('ID', '=', $empresa_id)->pluck('FILAS_NOTA_CREDITO');

        $DocNum = 0;    

        //dd($facturas);
        foreach ($facturas as $factura) {                      
            $DocNum += 1;     
            $factura->DocNum = $DocNum ;
            $factura->DocType = 'dDocument_Service';
            $factura->DocDate = $factura->FECHA_FACTURA->format('Y-m-d');
            $factura->DocDueDate = $factura->FECHA_FACTURA->format('Y-m-d');
            $factura->DocTaxDate = $factura->FECHA_FACTURA->format('Y-m-d');
            $factura->CardCode = $liquidacion->CODIGO_PROVEEDOR_SAP;
            $factura->NumAtCard = $factura->SERIE . ' - ' . $factura->NUMERO;
            $factura->DocCurrency = 'QTZ';
            $factura->SalesPersonCode = $codigoUsuarioSap;
            $factura->U_FacFecha = $factura->FECHA_FACTURA->format('Y-m-d');
            $factura->U_FacSerie = $factura->SERIE;
            $factura->U_FacNum = $factura->NUMERO;
            $factura->U_FacNum = $factura->NUMERO;
            $factura->U_FacNit = $factura->IDENTIFICADOR_TRIBUTARIO;
            $factura->U_FacNom = $factura->NOMBRE;
            $factura->U_Clase_Libro = $factura->GRUPOTIPOGASTO_ID;
            $factura->U_Tipo_Documento = $factura->DOCUMENTO;  
            if ($factura->TIPOGASTO == 'Alimentación') {
                if ($notaCredito == 0) {
                    if ($factura->MONTO_REMANENTE == null) {
                        $factura->Detalle = array(
                            array(
                                'ParentKey' => $factura->$DocNum,
                                'LineNum' => 0,
                                'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                'PriceAfterVAT' => $factura->TOTAL,
                                'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                'ProjectCode' => 'OOFISCAL',
                                'CostingCode' => $factura->CENTROCOSTO1,
                                'CostingCode2' => $factura->CENTROCOSTO2,
                                'CostingCode3' => $factura->CENTROCOSTO3,
                                'CostingCode4' => $factura->CENTROCOSTO4,
                                'CostingCode5' => $factura->CENTROCOSTO5
                            )
                        );                
                    } 
                } else { 
                    if ($factura->MONTO_REMANENTE == null) {
                        $factura->Detalle = array(
                            array(
                                'ParentKey' => $factura->$DocNum,
                                'LineNum' => 0,
                                'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                'PriceAfterVAT' => $factura->TOTAL,
                                'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                'ProjectCode' => 'OOFISCAL',
                                'CostingCode' => $factura->CENTROCOSTO1,
                                'CostingCode2' => $factura->CENTROCOSTO2,
                                'CostingCode3' => $factura->CENTROCOSTO3,
                                'CostingCode4' => $factura->CENTROCOSTO4,
                                'CostingCode5' => $factura->CENTROCOSTO5
                            )
                        );
                    } else {
                        $linea = 0;
                        $factura->Detalle = array(
                            array(
                                'ParentKey' => $factura->$DocNum,
                                'LineNum' => $linea + 1,
                                'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                'ProjectCode' => 'OOFISCAL',
                                'CostingCode' => $factura->CENTROCOSTO1,
                                'CostingCode2' => $factura->CENTROCOSTO2,
                                'CostingCode3' => $factura->CENTROCOSTO3,
                                'CostingCode4' => $factura->CENTROCOSTO4,
                                'CostingCode5' => $factura->CENTROCOSTO5,
                            ),
                            array(
                                'ParentKey_2' => $factura->$DocNum,
                                'LineNum_2' => $linea + 2,
                                'AccountCode_2' => $factura->CUENTA_CONTABLE_REMANENTE,
                                'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                'PriceAfterVAT_2' => $factura->MONTO_REMANENTE,
                                'TaxCode_2' => $factura->CODIGO_IMPUESTO_REMANENTE,
                                'ProjectCode_2' => 'OOFISCAL',
                                'CostingCode_2' => $factura->CENTROCOSTO1,
                                'CostingCode2_2' => $factura->CENTROCOSTO2,
                                'CostingCode3_2' => $factura->CENTROCOSTO3,
                                'CostingCode4_2' => $factura->CENTROCOSTO4,
                                'CostingCode5_2' => $factura->CENTROCOSTO5
                            )
                        );
                    }                   
                }
            }          
            
            if ($factura->TIPOGASTO == 'Combustible') {
                if ($notaCredito == 0) {
                    if ($factura->MONTO_REMANENTE == null) {
                        $linea = 0;
                        $factura->Detalle = array(
                            array(
                                'ParentKey' => $factura->$DocNum,
                                'LineNum' => $linea + 1,
                                'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                'ProjectCode' => 'OOFISCAL',
                                'CostingCode' => $factura->CENTROCOSTO1,
                                'CostingCode2' => $factura->CENTROCOSTO2,
                                'CostingCode3' => $factura->CENTROCOSTO3,
                                'CostingCode4' => $factura->CENTROCOSTO4,
                                'CostingCode5' => $factura->CENTROCOSTO5,
                            ),
                            array(
                                'ParentKey_2' => $factura->$DocNum,
                                'LineNum_2' => $linea + 2,
                                'AccountCode_2' => $factura->CUENTA_CONTABLE_EXENTO,
                                'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                'PriceAfterVAT_2' => $factura->MONTO_EXENTO,
                                'TaxCode_2' => $factura->CODIGO_IMPUESTO_EXENTO,
                                'ProjectCode_2' => 'OOFISCAL',
                                'CostingCode_2' => $factura->CENTROCOSTO1,
                                'CostingCode2_2' => $factura->CENTROCOSTO2,
                                'CostingCode3_2' => $factura->CENTROCOSTO3,
                                'CostingCode4_2' => $factura->CENTROCOSTO4,
                                'CostingCode5_2' => $factura->CENTROCOSTO5
                            )
                        );
                    }
                } else {
                    if ($factura->MONTO_REMANENTE == null) {                        
                        $linea = 0;
                        $factura->Detalle = array(
                            array(
                                'ParentKey' => $factura->$DocNum,
                                'LineNum' => $linea + 1,
                                'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                'ProjectCode' => 'OOFISCAL',
                                'CostingCode' => $factura->CENTROCOSTO1,
                                'CostingCode2' => $factura->CENTROCOSTO2,
                                'CostingCode3' => $factura->CENTROCOSTO3,
                                'CostingCode4' => $factura->CENTROCOSTO4,
                                'CostingCode5' => $factura->CENTROCOSTO5,
                            ),
                            array(
                                'ParentKey_2' => $factura->$DocNum,
                                'LineNum_2' => $linea + 2,
                                'AccountCode_2' => $factura->CUENTA_CONTABLE_EXENTO,
                                'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                'PriceAfterVAT_2' => $factura->MONTO_EXENTO,
                                'TaxCode_2' => $factura->CODIGO_IMPUESTO_EXENTO,
                                'ProjectCode_2' => 'OOFISCAL',
                                'CostingCode_2' => $factura->CENTROCOSTO1,
                                'CostingCode2_2' => $factura->CENTROCOSTO2,
                                'CostingCode3_2' => $factura->CENTROCOSTO3,
                                'CostingCode4_2' => $factura->CENTROCOSTO4,
                                'CostingCode5_2' => $factura->CENTROCOSTO5
                            )
                        );                        
                    } else {
                        $linea = 0;
                        $factura->Detalle = array(
                            array(   
                                'ParentKey' => $factura->$DocNum,
                                'LineNum' => $linea + 1,
                                'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                'ProjectCode' => 'OOFISCAL',
                                'CostingCode' => $factura->CENTROCOSTO1,
                                'CostingCode2' => $factura->CENTROCOSTO2,
                                'CostingCode3' => $factura->CENTROCOSTO3,
                                'CostingCode4' => $factura->CENTROCOSTO4,
                                'CostingCode5' => $factura->CENTROCOSTO5
                            ),
                            array(
                                'ParentKey_2' => $factura->$DocNum,
                                'LineNum_2' => $linea + 2,
                                'AccountCode_2' => $factura->CUENTA_CONTABLE_EXENTO,
                                'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                'PriceAfterVAT_2' => $factura->MONTO_EXENTO,
                                'TaxCode_2' => $factura->CODIGO_IMPUESTO_EXENTO,
                                'ProjectCode_2' => 'OOFISCAL',
                                'CostingCode_2' => $factura->CENTROCOSTO1,
                                'CostingCode2_2' => $factura->CENTROCOSTO2,
                                'CostingCode3_2' => $factura->CENTROCOSTO3,
                                'CostingCode4_2' => $factura->CENTROCOSTO4,
                                'CostingCode5_2' => $factura->CENTROCOSTO5
                            ),
                            array(
                                'ParentKey_3' => $factura->$DocNum,
                                'LineNum_3' => $linea + 3,
                                'AccountCode_3' => $factura->CUENTA_CONTABLE_REMANENTE,
                                'ItemDescription_3' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                'PriceAfterVAT_3' => $factura->MONTO_REMANENTE,
                                'TaxCode_3' => $factura->CODIGO_IMPUESTO_REMANENTE,
                                'ProjectCode_3' => 'OOFISCAL',
                                'CostingCode_3' => $factura->CENTROCOSTO1,
                                'CostingCode2_3' => $factura->CENTROCOSTO2,
                                'CostingCode3_3' => $factura->CENTROCOSTO3,
                                'CostingCode4_3' => $factura->CENTROCOSTO4,
                                'CostingCode5_3' => $factura->CENTROCOSTO5
                            )
                        );
                    }
                }
            }
            //Hospedaje
            if ($factura->TIPOGASTO == 'Hospedaje') {
                if ($notaCredito == 0) {
                    if ($factura->MONTO_EXENTO == null) {
                        if ($factura->MONTO_REMANENTE == null) {
                            $factura->Detalle = array(
                                array(
                                    'ParentKey' => $factura->$DocNum,
                                    'LineNum' => 0,
                                    'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                    'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT' => $factura->TOTAL,
                                    'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                    'ProjectCode' => 'OOFISCAL',
                                    'CostingCode' => $factura->CENTROCOSTO1,
                                    'CostingCode2' => $factura->CENTROCOSTO2,
                                    'CostingCode3' => $factura->CENTROCOSTO3,
                                    'CostingCode4' => $factura->CENTROCOSTO4,
                                    'CostingCode5' => $factura->CENTROCOSTO5
                                )
                            );    
                        }                     
                       
                    } else {                        
                        $linea = 0;
                        $factura->Detalle = array(
                            array(
                                'ParentKey' => $factura->$DocNum,
                                'LineNum' => $linea + 1,
                                'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                'ProjectCode' => 'OOFISCAL',
                                'CostingCode' => $factura->CENTROCOSTO1,
                                'CostingCode2' => $factura->CENTROCOSTO2,
                                'CostingCode3' => $factura->CENTROCOSTO3,
                                'CostingCode4' => $factura->CENTROCOSTO4,
                                'CostingCode5' => $factura->CENTROCOSTO5,
                            ),
                            array(
                                'ParentKey_2' => $factura->$DocNum,
                                'LineNum_2' => $linea + 2,
                                'AccountCode_2' => $factura->CUENTA_CONTABLE_EXENTO,
                                'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                'PriceAfterVAT_2' => $factura->MONTO_EXENTO,
                                'TaxCode_2' => $factura->CODIGO_IMPUESTO_EXENTO,
                                'ProjectCode_2' => 'OOFISCAL',
                                'CostingCode_2' => $factura->CENTROCOSTO1,
                                'CostingCode2_2' => $factura->CENTROCOSTO2,
                                'CostingCode3_2' => $factura->CENTROCOSTO3,
                                'CostingCode4_2' => $factura->CENTROCOSTO4,
                                'CostingCode5_2' => $factura->CENTROCOSTO5
                            )
                        );                     

                    }
                } else {
                    if ($factura->MONTO_EXENTO == null) {
                        if ($factura->MONTO_REMANENTE == null) {
                            $factura->Detalle = array(
                                array(
                                    'ParentKey' => $factura->$DocNum,
                                    'LineNum' => 0,
                                    'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                    'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT' => $factura->TOTAL,
                                    'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                    'ProjectCode' => 'OOFISCAL',
                                    'CostingCode' => $factura->CENTROCOSTO1,
                                    'CostingCode2' => $factura->CENTROCOSTO2,
                                    'CostingCode3' => $factura->CENTROCOSTO3,
                                    'CostingCode4' => $factura->CENTROCOSTO4,
                                    'CostingCode5' => $factura->CENTROCOSTO5
                                )
                            );    
                        } else {
                            $linea = 0;
                            $factura->Detalle = array(
                                array(
                                    'ParentKey' => $factura->$DocNum,
                                    'LineNum' => $linea + 1,
                                    'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                    'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                    'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                    'ProjectCode' => 'OOFISCAL',
                                    'CostingCode' => $factura->CENTROCOSTO1,
                                    'CostingCode2' => $factura->CENTROCOSTO2,
                                    'CostingCode3' => $factura->CENTROCOSTO3,
                                    'CostingCode4' => $factura->CENTROCOSTO4,
                                    'CostingCode5' => $factura->CENTROCOSTO5,
                                ),
                                array(
                                    'ParentKey_2' => $factura->$DocNum,
                                    'LineNum_2' => $linea + 2,
                                    'AccountCode_2' => $factura->CUENTA_CONTABLE_REMANENTE,
                                    'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT_2' => $factura->MONTO_REMANENTE,
                                    'TaxCode_2' => $factura->CODIGO_IMPUESTO_REMANENTE,
                                    'ProjectCode_2' => 'OOFISCAL',
                                    'CostingCode_2' => $factura->CENTROCOSTO1,
                                    'CostingCode2_2' => $factura->CENTROCOSTO2,
                                    'CostingCode3_2' => $factura->CENTROCOSTO3,
                                    'CostingCode4_2' => $factura->CENTROCOSTO4,
                                    'CostingCode5_2' => $factura->CENTROCOSTO5
                                )
                            );
                        }  
                    } else {
                        if ($factura->MONTO_REMANENTE == null) {                        
                            $linea = 0;
                            $factura->Detalle = array(
                                array(
                                    'ParentKey' => $factura->$DocNum,
                                    'LineNum' => $linea + 1,
                                    'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                    'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                    'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                    'ProjectCode' => 'OOFISCAL',
                                    'CostingCode' => $factura->CENTROCOSTO1,
                                    'CostingCode2' => $factura->CENTROCOSTO2,
                                    'CostingCode3' => $factura->CENTROCOSTO3,
                                    'CostingCode4' => $factura->CENTROCOSTO4,
                                    'CostingCode5' => $factura->CENTROCOSTO5,
                                ),
                                array(
                                    'ParentKey_2' => $factura->$DocNum,
                                    'LineNum_2' => $linea + 2,
                                    'AccountCode_2' => $factura->CUENTA_CONTABLE_EXENTO,
                                    'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT_2' => $factura->MONTO_EXENTO,
                                    'TaxCode_2' => $factura->CODIGO_IMPUESTO_EXENTO,
                                    'ProjectCode_2' => 'OOFISCAL',
                                    'CostingCode_2' => $factura->CENTROCOSTO1,
                                    'CostingCode2_2' => $factura->CENTROCOSTO2,
                                    'CostingCode3_2' => $factura->CENTROCOSTO3,
                                    'CostingCode4_2' => $factura->CENTROCOSTO4,
                                    'CostingCode5_2' => $factura->CENTROCOSTO5
                                )
                            );                        
                        } else {
                            $linea = 0;
                            $factura->Detalle = array(
                                array(   
                                    'ParentKey' => $factura->$DocNum,
                                    'LineNum' => $linea + 1,
                                    'AccountCode' => $factura->CUENTA_CONTABLE_AFECTO,
                                    'ItemDescription' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT' => $factura->MONTO_AFECTO . $factura->MONTO_IVA,
                                    'TaxCode' => $factura->CODIGO_IMPUESTO_AFECTO,
                                    'ProjectCode' => 'OOFISCAL',
                                    'CostingCode' => $factura->CENTROCOSTO1,
                                    'CostingCode2' => $factura->CENTROCOSTO2,
                                    'CostingCode3' => $factura->CENTROCOSTO3,
                                    'CostingCode4' => $factura->CENTROCOSTO4,
                                    'CostingCode5' => $factura->CENTROCOSTO5
                                ),
                                array(
                                    'ParentKey_2' => $factura->$DocNum,
                                    'LineNum_2' => $linea + 2,
                                    'AccountCode_2' => $factura->CUENTA_CONTABLE_EXENTO,
                                    'ItemDescription_2' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT_2' => $factura->MONTO_EXENTO,
                                    'TaxCode_2' => $factura->CODIGO_IMPUESTO_EXENTO,
                                    'ProjectCode_2' => 'OOFISCAL',
                                    'CostingCode_2' => $factura->CENTROCOSTO1,
                                    'CostingCode2_2' => $factura->CENTROCOSTO2,
                                    'CostingCode3_2' => $factura->CENTROCOSTO3,
                                    'CostingCode4_2' => $factura->CENTROCOSTO4,
                                    'CostingCode5_2' => $factura->CENTROCOSTO5
                                ),
                                array(
                                    'ParentKey_3' => $factura->$DocNum,
                                    'LineNum_3' => $linea + 3,
                                    'AccountCode_3' => $factura->CUENTA_CONTABLE_REMANENTE,
                                    'ItemDescription_3' => $factura->TIPOGASTO . ' ' . $factura->DOCUMENTO . ' ' . $factura->SERIE . '-' . $factura->NUMERO,
                                    'PriceAfterVAT_3' => $factura->MONTO_REMANENTE,
                                    'TaxCode_3' => $factura->CODIGO_IMPUESTO_REMANENTE,
                                    'ProjectCode_3' => 'OOFISCAL',
                                    'CostingCode_3' => $factura->CENTROCOSTO1,
                                    'CostingCode2_3' => $factura->CENTROCOSTO2,
                                    'CostingCode3_3' => $factura->CENTROCOSTO3,
                                    'CostingCode4_3' => $factura->CENTROCOSTO4,
                                    'CostingCode5_3' => $factura->CENTROCOSTO5
                                )
                            );
                        }

                    }                 

                }
            }
        }
        
        $facturas = $facturas->toArray();

        

        $facturas = array_map(function($fac) {
            return array_except($fac, ['ID', 'PROVEEDORID', 'NOMBRE', 'IDENTIFICADOR_TRIBUTARIO', 'SERIE', 'NUMERO',
                                       'TOTAL', 'FECHA_FACTURA', 'MONTO_IVA', 'TIPOGASTO', 'GRUPOTIPOGASTO_ID',
                                       'APROBACION_PAGO', 'DOCUMENTO', 'EMAIL', 'FOTO', 'TIPOPROVEEDOR_ID', 'MONTO_AFECTO',
                                       'MONTO_EXENTO', 'MONTO_REMANENTE', 'CENTROCOSTO1', 'CENTROCOSTO2', 'CENTROCOSTO3',
                                       'CENTROCOSTO4', 'CENTROCOSTO5', 'CUENTA_CONTABLE_EXENTO', 'CODIGO_IMPUESTO_EXENTO',
                                       'CUENTA_CONTABLE_AFECTO', 'CODIGO_IMPUESTO_AFECTO','CUENTA_CONTABLE_REMANENTE',
                                       'CODIGO_IMPUESTO_REMANENTE']);
        }, $facturas);
        
        
        $facturasFinal = array(
            'liquidacionId' => $liquidacion->ID,
            'items' => $facturas
        );

        //ie('<pre>'.print_r($facturas,true).'</pre>');
        //die(json_encode($facturas));
        dd($facturasFinal);

        return $facturas;
*/
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

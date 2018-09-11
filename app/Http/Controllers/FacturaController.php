<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Factura;
use DateTimeZone;
use App\Proveedor;
use App\TipoGasto;
use Carbon\Carbon;
use App\Liquidacion;
use App\Presupuesto;
use App\UsuarioRuta;
use App\Http\Requests;
use App\TipoDocumento;

use App\TipoProveedor;
use App\DetallePresupuesto;
use Illuminate\Http\Request;
use App\SubcategoriaTipoGasto;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\EditFacturaRequest;
use App\Http\Requests\CreateFacturaRequest;

class FacturaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('roles:superAdmin,vendedor,supervisor,contabilidad');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function liquidacionCreateFactura($id)
     {  
         $param = explode('-', $id);
         $liquidacion_id = $param[0];
         $tipoLiquidacion = $param[1];
        
         $empresa_id = Session::get('empresa');

         $subcategoria = null;//SubcategoriaTipoGasto::where('ANULADO', '=', 0)->lists('DESCRIPCION', 'ID')->toArray();
         $fechas =  Liquidacion::select('liq_liquidacion.FECHA_INICIO', 'liq_liquidacion.FECHA_FINAL', 'USUARIORUTA_ID' )
                                        ->where('liq_liquidacion.ID', '=', $liquidacion_id)
                                        ->first();


         $fechaInicio = $fechas->FECHA_INICIO;
         $fechaFinal = $fechas->FECHA_FINAL;

         $ruta = UsuarioRuta::select('cat_ruta.DEPRECIACION')
                                    ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                    ->where('cat_usuarioruta.ID', '=', $fechas->USUARIORUTA_ID)
                                    ->first();
                  

        /*** Tipo de Gasto Permitdos en Presupuesto ***/
        
        //if(($tipoLiquidacion == 'Otros Gastos') && ($ruta->DESCRIPCION == 'Depreciación') || ($ruta->DESCRIPCION == 'Depreciacion') )  {
        if($ruta->DEPRECIACION == 1 )  {
            //dd('ahi vamos');
            $tipoGasto =  TipoGasto::where('CONTROL_DEPRECIACION', '=', 1)
                                    ->where('EMPRESA_ID', '=', $empresa_id)
                                    ->lists('DESCRIPCION', 'ID')
                                    ->toArray();   
            
        } else {
            
            $tipoGasto =  Liquidacion::join('pre_presupuesto', 'pre_presupuesto.USUARIORUTA_ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
            ->join('pre_detpresupuesto', 'pre_detpresupuesto.PRESUPUESTO_ID', '=', 'pre_presupuesto.ID' )
            ->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'pre_detpresupuesto.TIPOGASTO_ID')
            ->whereDate('pre_presupuesto.VIGENCIA_INICIO', '<=', $fechaInicio)
            ->whereDate('pre_presupuesto.VIGENCIA_FINAL', '>=', $fechaFinal)
            ->where('liq_liquidacion.ID', '=', $liquidacion_id)
            ->lists('cat_tipogasto.DESCRIPCION', 'cat_tipogasto.ID')
            ->toArray();
        }

         

         /*** Se determina a que Presupuesto pertenece la Liquidación **/

         $presupuesto =  Liquidacion::join('pre_presupuesto', 'pre_presupuesto.USUARIORUTA_ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                                 ->whereDate('pre_presupuesto.VIGENCIA_INICIO', '<=', $fechaInicio)
                                                 ->whereDate('pre_presupuesto.VIGENCIA_FINAL', '>=', $fechaFinal)
                                                 ->where('liq_liquidacion.ID', '=', $liquidacion_id)
                                                 ->select('pre_presupuesto.ID')
                                                 ->first();

//dd($presupuesto);
         $proveedor = Proveedor::lists('IDENTIFICADOR_TRIBUTARIO', 'ID')
                                         ->toArray();

         $tipoProveedor = TipoProveedor::lists('DESCRIPCION', 'ID')->toArray();

         
         

        $monedaEmpresa = Empresa::select('MONEDA_LOCAL','MONEDA_SYS')->where('ID', '=', $empresa_id)->first();

         $fechaFactura = null;

         $tipoDocumento = TipoDocumento::lists('DESCRIPCION', 'ID')->toArray();

        // $factura->CANTIDAD_PORCENTAJE_CUSTOM = null;


         return view('facturas.create', compact('liquidacion_id', 'tipoGasto', 'proveedor', 'monedaEmpresa', 'fechaFactura', 'tipoProveedor',
                    'tipoDocumento', 'tipoLiquidacion', 'subcategoria', 'presupuesto'));
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFacturaRequest $request)
    {   
        //Se valida que fecha no sea anterior a X días programados por la empresa y dentro de Período de Liquidación

        $empresa = Session::get('loginEmpresa');
        
        if($request->TIPO_LIQUIDACION == 1) {            
            $restriccionDias = Empresa::select('TIEMPOATRASO_RUTAS')->where('ID', '=', $empresa)->first();
            $limite = Liquidacion::select('FECHA_INICIO', 'FECHA_FINAL')->where('ID', '=', $request->LIQUIDACION_ID)->first();
            //dd($limite->FECHA_INICIO->format('d-m-Y'));
            //dd($restriccionDias);
            
            $fechaFactura = new Carbon($request->FECHA_FACTURA);
            if ($fechaFactura->format('Y-m-d') < $limite->FECHA_INICIO->subDays($restriccionDias->TIEMPOATRASO_RUTAS)->format('Y-m-d')) {
                return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango de Liquidación Permitido!');            
            }
            if ($fechaFactura->format('Y-m-d') > $limite->FECHA_FINAL->format('Y-m-d')) {
                return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango de Liquidación Permitido!');            
            }    
        } else {
            $restriccionDias = Empresa::select('TIEMPOATRASO_OTROSGASTOS')->where('ID', '=', $empresa)->first();
            $limite = Liquidacion::select('FECHA_INICIO', 'FECHA_FINAL')->where('ID', '=', $request->LIQUIDACION_ID)->first();
            $fechaFactura = date_create($request->FECHA_FACTURA);
            if ($fechaFactura->format('Y-m-d') < $limite->FECHA_INICIO->subDays($restriccionDias->TIEMPOATRASO_OTROSGASTOS)->format('Y-m-d')) {
                return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango de Liquidación Permitido!');            
            }
            if ($fechaFactura->format('Y-m-d') > $limite->FECHA_FINAL->format('Y-m-d')) {
                return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango de Liquidación Permitido!');            
            }                
        }

         /** Se Determina si es Liquidación de Depreciación */

         $esDepreciacion = Liquidacion::join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                        ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                        ->where('liq_liquidacion.ID', '=', $request->LIQUIDACION_ID)
                                        ->where('cat_ruta.DEPRECIACION', '=', 1)
                                        ->select('cat_ruta.DEPRECIACION')
                                        ->first();        
       //dd($request->all());                               
        if($esDepreciacion) {            
            $detallePresupuesto = Presupuesto::join('pre_detpresupuesto', 'pre_detpresupuesto.PRESUPUESTO_ID', '=', 'pre_presupuesto.ID')
                                                ->select('pre_detpresupuesto.ID', 'pre_presupuesto.ASIGNACION_MENSUAL as MONTO')
                                                ->where('pre_presupuesto.ID', '=', $request->PRESUPUESTO_ID)->first();
            $detallePresupuesto->FRECUENCIATIEMPO_ID = 4;
            $detallePresupuesto->TIPOASIGNACION_ID = 1;

            $esCombustible = TipoGasto::join('cat_grupo_tipogasto', 'cat_grupo_tipogasto.ID', '=', 'cat_tipogasto.GRUPOTIPOGASTO_ID')
                                        ->join('pre_detpresupuesto', 'pre_detpresupuesto.TIPOGASTO_ID', '=', 'cat_tipogasto.ID')                                        
                                        ->where('cat_tipogasto.ID', '=', $request->TIPOGASTO_ID)                                        
                                        ->where('cat_grupo_tipogasto.ID', '=', 'BC')                                        
                                        ->where('pre_detpresupuesto.PRESUPUESTO_ID', '=', $request->PRESUPUESTO_ID)
                                        ->select('pre_detpresupuesto.ID','pre_detpresupuesto.TIPOASIGNACION_ID')
                                        ->first();
            if ($esCombustible) {
                $esCombustible->TIPOASIGNACION_ID = 1;            
            }                                                                                

            //dd('ahi vamos...' . $detallePresupuesto->MONTO);
        } else { 
            $detallePresupuesto = DetallePresupuesto::join('pre_presupuesto', 'pre_presupuesto.ID', '=', 'pre_detpresupuesto.PRESUPUESTO_ID')                                                        
                                                        ->where('pre_detpresupuesto.PRESUPUESTO_ID', '=', $request->PRESUPUESTO_ID)
                                                        ->where('pre_detpresupuesto.TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                                                        ->select('pre_detpresupuesto.ID', 'pre_detpresupuesto.MONTO', 'pre_detpresupuesto.FRECUENCIATIEMPO_ID', 'pre_detpresupuesto.TIPOASIGNACION_ID')
                                                        ->first();
                                                       
            $esCombustible = TipoGasto::join('cat_grupo_tipogasto', 'cat_grupo_tipogasto.ID', '=', 'cat_tipogasto.GRUPOTIPOGASTO_ID')
                                        ->join('pre_detpresupuesto', 'pre_detpresupuesto.TIPOGASTO_ID', '=', 'cat_tipogasto.ID')
                                        ->where('cat_tipogasto.ID', '=', $request->TIPOGASTO_ID)
                                        ->where('cat_grupo_tipogasto.ID', '=', 'BC')
                                        ->where('pre_detpresupuesto.PRESUPUESTO_ID', '=', $request->PRESUPUESTO_ID)
                                        ->select('pre_detpresupuesto.ID','pre_detpresupuesto.TIPOASIGNACION_ID')
                                        ->first();
        }       
       
        /**  Se valida si es combustible que se ingrese la cantidad correspondiente de galones y que Km Final sea > Km Inicial**/
        if ($esCombustible) {
            if($request->CANTIDAD_PORCENTAJE_CUSTOM === '') {
                return back()->withInput()->with('info', 'Es obligatorio que ingrese la cantidad de galones facturados!');
            } 
            if ($request->KM_INICIO == null || $request->KM_FINAL ==null) {
                return back()->withInput()->with('info', 'Es obligatorio que ingrese Kilometraje Inicial y Kilometraje Final!');
            }
            if ($request->KM_FINAL <= $request->KM_INICIO) {
                return back()->withInput()->with('info', 'El Kilometraje Inicial debe ser Mayor al Kilometraje Final!');
            }
        }  
       
        /** Se valida que fecha ingresada no sea mayor a fecha del día */       
        if ($request->FECHA_FACTURA > (Carbon::now(new DateTimeZone('America/Guatemala')))) {
            return back()->withInput()->with('info', 'La Fecha de la Factura, no puede ser mayor a la Fecha de Hoy!');        
        }
        
        
        if (trim($request->FMONEDA_ID) == 'USD') { 
            $montoConversion = round(($request->TOTAL * $request->TASA_CAMBIO), 4);           
        } else {
            $montoConversion = $request->TOTAL * 1;
        }

        /** Se valida que factura sea ingresada una sola vez */
        $datosFactura = Factura::select('PROVEEDOR_ID', 'SERIE', 'NUMERO')->get();
       
        foreach ($datosFactura as $datos) {       
            if(($datos->PROVEEDOR_ID == $request->PROVEEDOR_ID) && ($datos->SERIE == $request->SERIE) && ($datos->NUMERO == $request->NUMERO) ) {
                return back()->withInput()->with('info', 'La Factura Ya existe en la Base de Datos!!');
            }
        }
        

        /** Se obtiene valor de impuesto */
        $empresa_id = Session::get('empresa');
        $valorImpuesto = EMPRESA::select('IMPUESTO')->where('ID', '=', $empresa_id)->first();
        $valorImpuesto = round(($valorImpuesto->IMPUESTO / 100), 4);      
        

        /** Procesa Imagen **/

        $file = $request->file('FOTO');
        $name = $request->LIQUIDACION_ID . '-' . $request->NUMERO . '-' . time() . '-' . $file->getClientOriginalName();

        $path = public_path() . '/images/' .  Auth::user()->email ;

        if (file_exists($path)) {

        } else {
            mkdir($path, 0700);
        }
        $file->move($path,$name);

       
                                                                       
                                                                       
        /** Se obtiene No. de Detalle Presupuesto al que Corresponde **/

        /* if(($request->TIPO_LIQUIDACION == 'Otros Gastos') && ((strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACIóN') || strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACION') ) {             */
        /* if($esDepreciacion) {            
            $detallePresupuesto = Presupuesto::select('ID', 'ASIGNACION_MENSUAL as MONTO')->where('ID', '=', $request->PRESUPUESTO_ID)->first();
            $frecuenciaPresupuesto = 4;
            //dd('ahi vamos...' . $detallePresupuesto->MONTO);
        } else {            
            $detallePresupuesto = DetallePresupuesto::select('ID', 'MONTO')
                                                        ->where('PRESUPUESTO_ID', '=', $request->PRESUPUESTO_ID)
                                                        ->where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                                                        ->first();
            $frecuenciaPresupuesto = DetallePresupuesto::where('ID', '=', $detallePresupuesto->ID)->pluck('FRECUENCIATIEMPO_ID');
        } */
                     
        
        /** Validación de Frecuencia de Periodos */       
        if($detallePresupuesto->FRECUENCIATIEMPO_ID == 1) { 
            /** Se obtiene monto gastado hasta el momento por tipo de gasto **/
            if ($esCombustible && ($esCombustible->TIPOASIGNACION_ID == 2)) {                      
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                                            ->where('ANULADO', '=', 0)
                                            ->where('DETPRESUPUESTO_ID', '=', $detallePresupuesto->ID)
                                            ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfDay(), (new Carbon($request->FECHA_FACTURA))->endOfDay()])
                                            ->sum('CANTIDAD_PORCENTAJE_CUSTOM');
                
            } elseif ($esDepreciacion) {            
                $montoAcumulado = Factura::where('DETPRESUPUESTO_ID', '=', $detallePresupuesto->ID)            
                                            ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfDay(), (new Carbon($request->FECHA_FACTURA))->endOfDay()])
                                            ->where('ANULADO', '=', 0)
                                            ->sum('TOTAL');
            } else {             
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                                            ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfDay(), (new Carbon($request->FECHA_FACTURA))->endOfDay()])
                                            ->where('ANULADO', '=', 0)
                                            ->where('DETPRESUPUESTO_ID', '=', $detallePresupuesto->ID)
                                            ->sum('TOTAL');                        
            }
        } elseif ($detallePresupuesto->FRECUENCIATIEMPO_ID == 2) {             
            /** Se obtiene monto gastado hasta el momento por tipo de gasto **/
            if ($esCombustible && ($esCombustible->TIPOASIGNACION_ID == 2)) {       
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                                            ->where('ANULADO', '=', 0)
                                            ->where('DETPRESUPUESTO_ID', '=', $detallePresupuesto->ID)
                                            ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                                            ->sum('CANTIDAD_PORCENTAJE_CUSTOM');                
            } elseif ($esDepreciacion) {                       
                $montoAcumulado = Factura::where('DETPRESUPUESTO_ID', '=', $detallePresupuesto->ID)            
                                            ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                                            ->where('ANULADO', '=', 0)
                                            ->sum('TOTAL');
            } else {                            
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                                            ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                                            ->where('ANULADO', '=', 0)
                                            ->where('DETPRESUPUESTO_ID', '=', $detallePresupuesto->ID)                
                                            ->sum('TOTAL');                           
            }
        } elseif ($detallePresupuesto->FRECUENCIATIEMPO_ID == 4) {
            /** Se obtiene monto gastado hasta el momento por tipo de gasto **/
            if ($esCombustible && ($esCombustible->TIPOASIGNACION_ID == 2)) {                      
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                                            ->where('ANULADO', '=', 0)
                                            ->where('DETPRESUPUESTO_ID', '=', $detallePresupuesto->ID)
                                            ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfMonth(), (new Carbon($request->FECHA_FACTURA))->endOfMonth()])
                                            ->sum('CANTIDAD_PORCENTAJE_CUSTOM');
                
            } elseif ($esDepreciacion) {   
                $montoAcumulado = Factura::where('DETPRESUPUESTO_ID', '=', $detallePresupuesto->ID)
                                            ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfMonth(), (new Carbon($request->FECHA_FACTURA))->endOfMonth()])                
                                            ->where('ANULADO', '=', 0)
                                            ->sum('TOTAL');
            } else {             
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                                            ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfMonth(), (new Carbon($request->FECHA_FACTURA))->endOfMonth()])
                                            ->where('DETPRESUPUESTO_ID', '=', $detallePresupuesto->ID)
                                            ->where('ANULADO', '=', 0)
                                            ->sum('TOTAL');                        
            }
        } elseif ($detallePresupuesto->FRECUENCIATIEMPO_ID == 5) {
            /** Se obtiene monto gastado hasta el momento por tipo de gasto **/
            if ($esCombustible && ($esCombustible->TIPOASIGNACION_ID == 2)) {                      
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                                            ->where('ANULADO', '=', 0)
                                            ->where('DETPRESUPUESTO_ID', '=', $detallePresupuesto->ID)                                            
                                            ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfYear(), (new Carbon($request->FECHA_FACTURA))->endOfYear()])
                                            ->sum('CANTIDAD_PORCENTAJE_CUSTOM');
                
            } elseif ($esDepreciacion) {            
                $montoAcumulado = Factura::where('DETPRESUPUESTO_ID', '=', $detallePresupuesto->ID)            
                                            ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfYear(), (new Carbon($request->FECHA_FACTURA))->endOfYear()])                                            
                                            ->where('ANULADO', '=', 0)
                                            ->sum('TOTAL');
            } else {             
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                                            ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfYear(), (new Carbon($request->FECHA_FACTURA))->endOfYear()])
                                            ->where('DETPRESUPUESTO_ID', '=', $detallePresupuesto->ID)
                                            ->where('ANULADO', '=', 0)
                                            ->sum('TOTAL');                        
            }
        }   
         //dd($montoAcumulado);
        //echo('Presupuesto: ' . $detallePresupuesto->MONTO . ' Monto Acumulado: ' . $montoAcumulado . '<br>');
        $saldo = $detallePresupuesto->MONTO - $montoAcumulado;        
               
        /** Se Identifica Si Tipo de Gasto Tiene Impuesto a Aplicar */
        
        $impuestoAplicar = SubcategoriaTipoGasto::where('ID', '=', $request->subcategoriaTipoGasto)->pluck('MONTO_A_APLICAR');

        $factura = new Factura();
        /** Aqui iba lo anterior */
        $factura->CANTIDAD_PORCENTAJE_CUSTOM = $request->CANTIDAD_PORCENTAJE_CUSTOM;
        
        $factura = calculos($factura, $esCombustible, $montoConversion, $valorImpuesto, $saldo, $impuestoAplicar);
        
        if ($request->CANTIDAD_PORCENTAJE_CUSTOM == '') {
            $request->CANTIDAD_PORCENTAJE_CUSTOM = 0.00;
        } 
        if($request->TASA_CAMBIO == '') {            
            $request->TASA_CAMBIO = 1.00;
        }
        
        //dd('vamos mal o punto de control');
        $factura->LIQUIDACION_ID = $request->LIQUIDACION_ID;
        $factura->TIPOGASTO_ID = $request->TIPOGASTO_ID;
        $factura->SUBCATEGORIA_TIPOGASTO_ID = $request->subcategoriaTipoGasto;
        $factura->DETPRESUPUESTO_ID = $detallePresupuesto->ID;
        $factura->MONEDA_ID = $request->FMONEDA_ID;
        $factura->PROVEEDOR_ID = $request->PROVEEDOR_ID;        
        $factura->SERIE = $request->SERIE;
        $factura->NUMERO = $request->NUMERO;
        $factura->FECHA_FACTURA = $request->FECHA_FACTURA;
        $factura->CANTIDAD_PORCENTAJE_CUSTOM = $request->CANTIDAD_PORCENTAJE_CUSTOM;
        $factura->TIPODOCUMENTO_ID = $request->TIPODOCUMENTO_ID;
        $factura->KILOMETRAJE_INICIAL = $request->KM_INICIO;
        $factura->KILOMETRAJE_FINAL = $request->KM_FINAL;
        $factura->COMENTARIO_PAGO = $request->COMENTARIO_PAGO;
        $factura->TOTAL = $request->TOTAL;
        $factura->MONTO_ORIGINAL = $request->TOTAL;
        $factura->TASA_CAMBIO = $request->TASA_CAMBIO;
        $factura->MONTO_CONVERSION = $montoConversion;
        $factura->FOTO = $name;
        $factura->ANULADO = 0;

        $factura->save();

        return Redirect::to('liquidaciones/' . $request->LIQUIDACION_ID . '-' . $request->TIPO_LIQUIDACION . '/edit');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tipoGasto($id)
    {
        $tipoGasto = TipoGasto::select('UNIDAD_MEDIDA')->where('ID', '=', $id)->first();
        return $tipoGasto->UNIDAD_MEDIDA;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $proveedor = Proveedor::select('NOMBRE')->where('ID', '=', $id)->first();
        return $proveedor->NOMBRE;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $param = explode('-', $id);
        $liquidacion_id = $param[0];
        $factura_id = $param[1];
        $tipoLiquidacion = $param[2];
        $factura = Factura::findOrFail($factura_id);

        $subcategoria = SubcategoriaTipoGasto::where('ANULADO', '=', 0)->where('TIPOGASTO_ID', '=', $factura->TIPOGASTO_ID)->lists('DESCRIPCION', 'ID')->toArray();
        $fechas =  Liquidacion::select('liq_liquidacion.FECHA_INICIO', 'liq_liquidacion.FECHA_FINAL')
            ->where('liq_liquidacion.ID', '=', $liquidacion_id)
            ->first();


        $fechaInicio = $fechas->FECHA_INICIO;
        $fechaFinal = $fechas->FECHA_FINAL;

        $liquidacion_id = $factura->LIQUIDACION_ID;

        $tipoGasto = TipoGasto::lists('DESCRIPCION', 'ID')
                                        ->toArray();

        $proveedor = Proveedor::lists('IDENTIFICADOR_TRIBUTARIO', 'ID')
                                        ->toArray();

        $tipoProveedor = TipoProveedor::lists('DESCRIPCION', 'ID')->toArray();

        $empresa_id = Session::get('empresa');
       

        $monedaEmpresa = Empresa::select('MONEDA_LOCAL','MONEDA_SYS')->where('ID', '=', $empresa_id)->first();

        $fechaFactura = $factura->FECHA_FACTURA;

        $factura->EMAIL = Auth::user()->email;

        $tipoDocumento = TipoDocumento::lists('DESCRIPCION', 'ID')->toArray();

        /*** Se determina a que Presupuesto pertenece la Liquidación **/

        $presupuesto =  Liquidacion::join('pre_presupuesto', 'pre_presupuesto.USUARIORUTA_ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
            ->whereDate('pre_presupuesto.VIGENCIA_INICIO', '<=', $fechaInicio)
            ->whereDate('pre_presupuesto.VIGENCIA_FINAL', '>=', $fechaFinal)
            ->where('liq_liquidacion.ID', '=', $liquidacion_id)
            ->select('pre_presupuesto.ID')
            ->first();
            

        return view('facturas.edit', compact('factura', 'tipoGasto', 'proveedor', 'monedaEmpresa', 'fechaFactura', 'tipoProveedor', 'liquidacion_id', 'tipoDocumento',
                    'tipoLiquidacion', 'presupuesto', 'subcategoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditFacturaRequest $request, $id)
    {
        //Se valida que fecha no sea anterior a X días programados por la empresa y dentro de Período de Liquidación
        $factura = Factura::findOrFail($id);
        $empresa = Session::get('loginEmpresa');
        
        if($request->TIPO_LIQUIDACION == 1) {            
            $restriccionDias = Empresa::select('TIEMPOATRASO_RUTAS')->where('ID', '=', $empresa)->first();
            $limite = Liquidacion::select('FECHA_INICIO', 'FECHA_FINAL')->where('ID', '=', $request->LIQUIDACION_ID)->first();
            //dd($limite->FECHA_INICIO->format('d-m-Y'));
            //dd($restriccionDias);
            
            $fechaFactura = new Carbon($request->FECHA_FACTURA);
            if ($fechaFactura->format('Y-m-d') < $limite->FECHA_INICIO->subDays($restriccionDias->TIEMPOATRASO_RUTAS)->format('Y-m-d')) {
                return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango de Liquidación Permitido!');            
            }
            if ($fechaFactura->format('Y-m-d') > $limite->FECHA_FINAL->format('Y-m-d')) {
                return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango de Liquidación Permitido!');            
            }    
        } else {
            $restriccionDias = Empresa::select('TIEMPOATRASO_OTROSGASTOS')->where('ID', '=', $empresa)->first();
            $limite = Liquidacion::select('FECHA_INICIO', 'FECHA_FINAL')->where('ID', '=', $request->LIQUIDACION_ID)->first();
            $fechaFactura = date_create($request->FECHA_FACTURA);
            if ($fechaFactura->format('Y-m-d') < $limite->FECHA_INICIO->subDays($restriccionDias->TIEMPOATRASO_OTROSGASTOS)->format('Y-m-d')) {
                return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango de Liquidación Permitido!');            
            }
            if ($fechaFactura->format('Y-m-d') > $limite->FECHA_FINAL->format('Y-m-d')) {
                return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango de Liquidación Permitido!');            
            }                
        }

        $esDepreciacion = Liquidacion::join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                        ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                        ->where('liq_liquidacion.ID', '=', $request->LIQUIDACION_ID)
                                        ->where('cat_ruta.DEPRECIACION', '=', 1)
                                        ->select('cat_ruta.DEPRECIACION')
                                        ->first();  

        if($esDepreciacion) {            
            $detallePresupuesto = Presupuesto::join('pre_detpresupuesto', 'pre_detpresupuesto.PRESUPUESTO_ID', '=', 'pre_presupuesto.ID')
                                                ->select('pre_detpresupuesto.ID', 'pre_presupuesto.ASIGNACION_MENSUAL as MONTO')
                                                ->where('pre_presupuesto.ID', '=', $request->PRESUPUESTO_ID)->first();
            $detallePresupuesto->FRECUENCIATIEMPO_ID = 4;
            $detallePresupuesto->TIPOASIGNACION_ID = 1;

            $esCombustible = TipoGasto::join('cat_grupo_tipogasto', 'cat_grupo_tipogasto.ID', '=', 'cat_tipogasto.GRUPOTIPOGASTO_ID')
                                        ->join('pre_detpresupuesto', 'pre_detpresupuesto.TIPOGASTO_ID', '=', 'cat_tipogasto.ID')                                        
                                        ->where('cat_tipogasto.ID', '=', $request->TIPOGASTO_ID)                                        
                                        ->where('cat_grupo_tipogasto.ID', '=', 'BC')                                        
                                        ->where('pre_detpresupuesto.PRESUPUESTO_ID', '=', $request->PRESUPUESTO_ID)
                                        ->select('pre_detpresupuesto.ID','pre_detpresupuesto.TIPOASIGNACION_ID')
                                        ->first();
            if ($esCombustible) {
                $esCombustible->TIPOASIGNACION_ID = 1;            
            }                                                                                

            //dd('ahi vamos...' . $detallePresupuesto->MONTO);
        } else { 
            $detallePresupuesto = DetallePresupuesto::select('MONTO', 'FRECUENCIATIEMPO_ID', 'TIPOASIGNACION_ID')
                                                        ->where('ID', '=', $factura->DETPRESUPUESTO_ID)
                                                        ->first();

            $esCombustible = TipoGasto::join('cat_grupo_tipogasto', 'cat_grupo_tipogasto.ID', '=', 'cat_tipogasto.GRUPOTIPOGASTO_ID')
                                        ->join('pre_detpresupuesto', 'pre_detpresupuesto.TIPOGASTO_ID', '=', 'cat_tipogasto.ID')
                                        ->where('cat_tipogasto.ID', '=', $request->TIPOGASTO_ID)
                                        ->where('cat_grupo_tipogasto.ID', '=', 'BC')
                                        ->where('pre_detpresupuesto.PRESUPUESTO_ID', '=', $request->PRESUPUESTO_ID)
                                        ->select('pre_detpresupuesto.ID','pre_detpresupuesto.TIPOASIGNACION_ID')
                                        ->first();
        }                                       

        /**  Se valida si es combustible que se ingrese la cantidad correspondiente de galones y que Km Final sea > Km Inicial**/
        if ($esCombustible) {
            if($request->CANTIDAD_PORCENTAJE_CUSTOM === '') {
                return back()->withInput()->with('info', 'Es obligatorio que ingrese la cantidad de galones facturados!');
            } 
            if ($request->KM_INICIO == null || $request->KM_FINAL ==null) {
                return back()->withInput()->with('info', 'Es obligatorio que ingrese Kilometraje Inicial y Kilometraje Final!');
            }
            if ($request->KM_FINAL <= $request->KM_INICIO) {
                return back()->withInput()->with('info', 'El Kilometraje Inicial debe ser Mayor al Kilometraje Final!');
            }
        }  
        
        /** Se valida que fecha ingresada no sea mayor a fecha del día */       
        if ($request->FECHA_FACTURA > (Carbon::now(new DateTimeZone('America/Guatemala')))) {
            return back()->withInput()->with('info', 'La Fecha de la Factura, no puede ser mayor a la Fecha de Hoy!');        
        }
       

        if (trim($request->FMONEDA_ID) == 'USD') {            
            $montoConversion = round(($request->TOTAL * $request->TASA_CAMBIO), 4);
            //dd($montoConversion);
        } else {
            $montoConversion = $request->TOTAL * 1;
        } 
        
        /** Se valida que factura sea ingresada una sola vez */
        //dd($request->all());
        if(($factura->PROVEEDOR_ID != $request->PROVEEDOR_ID) || ($factura->SERIE != $request->SERIE) || ($factura->NUMERO != $request->NUMERO)) {
            $datosFactura = Factura::select('PROVEEDOR_ID', 'SERIE', 'NUMERO')->get();
            foreach ($datosFactura as $datos) {               
                if(($datos->PROVEEDOR_ID == $request->PROVEEDOR_ID) && ($datos->SERIE == $request->SERIE) && ($datos->NUMERO == $request->NUMERO) ) {
                    return back()->withInput()->with('info', 'La Factura Ya existe en la Base de Datos!!');
                }
            }
        } 

        /** Si hubo hubo cambio en Monto o Cantidad de Galones **/

        if (($factura->TOTAL != $request->TOTAL) || ($factura->CANTIDAD_PORCENTAJE_CUSTOM != $request->CANTIDAD_PORCENTAJE_CUSTOM))  { 
           
            Factura::where('ID', '=', $factura->ID)
                ->update(['TOTAL' => $request->TOTAL, 'MONTO_ORIGINAL' => $request->TOTAL, 'MONTO_CONVERSION' => $montoConversion,
                          'CANTIDAD_PORCENTAJE_CUSTOM' => $request->CANTIDAD_PORCENTAJE_CUSTOM, 'TASA_CAMBIO' => $request->TASA_CAMBIO]);


            /** Se obtiene valor de impuesto */
            $empresa_id = Session::get('empresa');
            $valorImpuesto = EMPRESA::select('IMPUESTO')->where('ID', '=', $empresa_id)->first();
            $valorImpuesto = round(($valorImpuesto->IMPUESTO / 100), 4); 
            $impuestoAplicar = SubcategoriaTipoGasto::where('ID', '=', $factura->SUBCATEGORIA_TIPOGASTO_ID)->pluck('MONTO_A_APLICAR');
            
            
            // /** Se Determina si es Liquidación de Depreciación y si se obtiene datos del Presupuesto **/
            // $esDepreciacion = Liquidacion::join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
            //                                 ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
            //                                 ->where('liq_liquidacion.ID', '=', $factura->LIQUIDACION_ID)
            //                                 ->where('cat_ruta.DEPRECIACION', '=', 1)
            //                                 ->select('cat_ruta.DEPRECIACION')
            //                                 ->first();

            // if($esDepreciacion) {            
            //     $detallePresupuesto = Presupuesto::select('ASIGNACION_MENSUAL as MONTO')->where('ID', '=', $factura->PRESUPUESTO_ID)->first();
            //     $detallePresupuesto->FRECUENCIATIEMPO_ID = 4;
            //     $detallePresupuesto->TIPOASIGNACION_ID = 1;
            //     //dd('ahi vamos...' . $detallePresupuesto->MONTO);
            // } else {
            //     $detallePresupuesto = DetallePresupuesto::select('MONTO', 'FRECUENCIATIEMPO_ID', 'TIPOASIGNACION_ID')
            //                                                 ->where('ID', '=', $factura->DETPRESUPUESTO_ID)
            //                                                 ->first();
            // }


            if ($detallePresupuesto->FRECUENCIATIEMPO_ID == 2) {
                $fechaInicio = (new Carbon($factura->FECHA_FACTURA))->startOfWeek();
                $fechaFinal = (new Carbon($factura->FECHA_FACTURA))->endOfWeek();
            } 
            if ($detallePresupuesto->FRECUENCIATIEMPO_ID == 4) {
                $fechaInicio = (new Carbon($factura->FECHA_FACTURA))->startOfMonth();
                $fechaFinal = (new Carbon($factura->FECHA_FACTURA))->endOfMonth();
            } 
            if ($detallePresupuesto->FRECUENCIATIEMPO_ID == 5) {
                $fechaInicio = (new Carbon($factura->FECHA_FACTURA))->startOfYear();
                $fechaFinal = (new Carbon($factura->FECHA_FACTURA))->endOfYear();
            } 

            /** Se obtienen facturas del mismo Presupuesto */

            $compartenPresupuesto = Factura::select('ID', 'MONTO_AFECTO', 'MONTO_EXENTO', 'MONTO_IVA', 'CANTIDAD_PORCENTAJE_CUSTOM',
                                                    'MONTO_REMANENTE', 'MONTO_CONVERSION', 'APROBACION_PAGO')
                                            ->where('LIQUIDACION_ID', '=', $factura->LIQUIDACION_ID)
                                            ->where('DETPRESUPUESTO_ID', '=', $factura->DETPRESUPUESTO_ID)
                                            ->where('ANULADO', '=', 0)
                                            ->whereBetween('FECHA_FACTURA', [$fechaInicio, $fechaFinal])                                            
                                            ->get();

            $montoAcumulado = 0;
           
//dd($compartenPresupuesto);
            foreach ($compartenPresupuesto as $item) { 
                $saldo = $detallePresupuesto->MONTO - $montoAcumulado; 
                  
                echo 'Acumulado: ' . $montoAcumulado . '<br>';                   
                $factura = calculos($item, $esCombustible, $item->MONTO_CONVERSION, $valorImpuesto, $saldo, $impuestoAplicar);
                if (isset($esCombustible)) {
                    if ($esCombustible->TIPOASIGNACION_ID == 2) { 
                        $montoAcumulado += $item->CANTIDAD_PORCENTAJE_CUSTOM;
                    } else {
                        $montoAcumulado += $item->MONTO_CONVERSION;
                    }
                } else {                    
                    $montoAcumulado += $item->MONTO_CONVERSION;                                        
                }
                Factura::where('ID', '=', $factura->ID)
                            ->update(['MONTO_AFECTO' => $factura->MONTO_AFECTO, 'MONTO_EXENTO' => $factura->MONTO_EXENTO, 'MONTO_IVA' => $factura->MONTO_IVA,
                                      'CANTIDAD_PORCENTAJE_CUSTOM' => $factura->CANTIDAD_PORCENTAJE_CUSTOM, 'MONTO_REMANENTE' => $factura->MONTO_REMANENTE,
                                      'MONTO_CONVERSION' => $factura->MONTO_CONVERSION, 'APROBACION_PAGO' => $factura->APROBACION_PAGO]);
                echo $factura . '<br>';
            }

        }           
        
        if ($request->CANTIDAD_PORCENTAJE_CUSTOM == '') {
            $request->CANTIDAD_PORCENTAJE_CUSTOM = 0.00;
        } 
        
        Factura::where('ID', $id)
                ->update(['TIPOGASTO_ID' => $request->TIPOGASTO_ID, 'MONEDA_ID' => $request->FMONEDA_ID, 'PROVEEDOR_ID' => $request->PROVEEDOR_ID, 
                          'KILOMETRAJE_INICIAL' => $request->KM_INICIO, 'KILOMETRAJE_FINAL' => $request->KM_FINAL, 'CORRECCION' => 0, 
                          'SUBCATEGORIA_TIPOGASTO_ID' => $request->subcategoriaTipoGasto, 'SERIE' => $request->SERIE, 'NUMERO' => $request->NUMERO, 
                          'FECHA_FACTURA' => $request->FECHA_FACTURA, 'COMENTARIO_PAGO' => $request->COMENTARIO_PAGO, 'TIPODOCUMENTO_ID' => $request->TIPODOCUMENTO_ID,]);
                          

        return Redirect::to('liquidaciones/' . $request->LIQUIDACION_ID . '-' . $request->TIPO_LIQUIDACION . '/edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sustituirFactura(Request $request)
    {   
        /** Se elimina fisicamente el archivo del disco duro */
        $urlFile = public_path() . '/images/' . $request->URL_IMAGENFACTURA;
        chmod($urlFile, 0777);
        if (!unlink($urlFile)) {
            dd('Archivo NO fue Eliminado');
        }

        /** Se Procesa el nuevo archivo  */
        $file = $request->file('FOTO');        

        $name = $request->LIQUIDACION_ID . '-' . $request->NUMERO . '-' . time() . '-' . $file->getClientOriginalName();

        $path = public_path() . '/images/' .  Auth::user()->email ;

        if (file_exists($path)) {

        } else {
            mkdir($path, 0700);
        }

        $file->move($path,$name);

        Factura::where('ID', $request->ID_FACTURA)
        ->update(['FOTO' => $name]);
        

        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateComentarioFactura(Request $request, $id)
    {       
        $comentarioActual = Factura::select('COMENTARIO_SUPERVISOR')->where('ID', '=', $id)->first();
        $nuevoComentario = $comentarioActual->COMENTARIO_SUPERVISOR . ' ' . $request->COMENTARIO_SUPERVISOR;
        Factura::where('ID', $id)
                ->update(['COMENTARIO_SUPERVISOR' => $nuevoComentario, 'CORRECCION' => 1]);

        return $request->COMENTARIO_SUPERVISOR;

        return Redirect::to('liquidaciones');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateComentarioContabilidadFactura(Request $request, $id)
    {
        $comentarioActual = Factura::select('COMENTARIO_CONTABILIDAD')->where('ID', '=', $id)->first();
        $nuevoComentario = $comentarioActual->COMENTARIO_CONTABILIDAD . ' ' . $request->COMENTARIO_CONTABILIDAD;

        Factura::where('ID', $id)
                ->update(['COMENTARIO_CONTABILIDAD' => $nuevoComentario, 'CORRECCION' => 1]);

        return $nuevoComentario;

        return Redirect::to('liquidaciones');
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
        $factura = Factura::findOrFail($id);
        $infoExtra = Factura::join('pre_detpresupuesto', 'pre_detpresupuesto.ID', '=', 'liq_factura.DETPRESUPUESTO_ID')
                                ->join('pre_presupuesto', 'pre_presupuesto.ID', '=', 'pre_detpresupuesto.PRESUPUESTO_ID')
                                ->where('pre_detpresupuesto.ID', '=', $factura->DETPRESUPUESTO_ID)
                                ->select('pre_presupuesto.ID as PRESUPUESTO_ID')
                                ->first();
                                
        $esDepreciacion = Liquidacion::join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                        ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                        ->where('liq_liquidacion.ID', '=', $factura->LIQUIDACION_ID)
                                        ->where('cat_ruta.DEPRECIACION', '=', 1)
                                        ->select('cat_ruta.DEPRECIACION')
                                        ->first();  

        if($esDepreciacion) {            
            $detallePresupuesto = Presupuesto::join('pre_detpresupuesto', 'pre_detpresupuesto.PRESUPUESTO_ID', '=', 'pre_presupuesto.ID')
                                                ->select('pre_detpresupuesto.ID', 'pre_presupuesto.ASIGNACION_MENSUAL as MONTO')
                                                ->where('pre_presupuesto.ID', '=', $infoExtra->PRESUPUESTO_ID)->first();
            $detallePresupuesto->FRECUENCIATIEMPO_ID = 4;
            $detallePresupuesto->TIPOASIGNACION_ID = 1;

            $esCombustible = TipoGasto::join('cat_grupo_tipogasto', 'cat_grupo_tipogasto.ID', '=', 'cat_tipogasto.GRUPOTIPOGASTO_ID')
                                        ->join('pre_detpresupuesto', 'pre_detpresupuesto.TIPOGASTO_ID', '=', 'cat_tipogasto.ID')                                        
                                        ->where('cat_tipogasto.ID', '=', $factura->TIPOGASTO_ID)                                        
                                        ->where('cat_grupo_tipogasto.ID', '=', 'BC')                                        
                                        ->where('pre_detpresupuesto.PRESUPUESTO_ID', '=', $infoExtra->PRESUPUESTO_ID)
                                        ->select('pre_detpresupuesto.ID','pre_detpresupuesto.TIPOASIGNACION_ID')
                                        ->first();
            if ($esCombustible) {
                $esCombustible->TIPOASIGNACION_ID = 1;            
            }                                                                                

            //dd('ahi vamos...' . $detallePresupuesto->MONTO);
        } else { 
            $detallePresupuesto = DetallePresupuesto::select('MONTO', 'FRECUENCIATIEMPO_ID', 'TIPOASIGNACION_ID')
                                                        ->where('ID', '=', $factura->DETPRESUPUESTO_ID)
                                                        ->first();

            $esCombustible = TipoGasto::join('cat_grupo_tipogasto', 'cat_grupo_tipogasto.ID', '=', 'cat_tipogasto.GRUPOTIPOGASTO_ID')
                                        ->join('pre_detpresupuesto', 'pre_detpresupuesto.TIPOGASTO_ID', '=', 'cat_tipogasto.ID')
                                        ->where('cat_tipogasto.ID', '=', $factura->TIPOGASTO_ID)
                                        ->where('cat_grupo_tipogasto.ID', '=', 'BC')
                                        ->where('pre_detpresupuesto.PRESUPUESTO_ID', '=', $infoExtra->PRESUPUESTO_ID)
                                        ->select('pre_detpresupuesto.ID','pre_detpresupuesto.TIPOASIGNACION_ID')
                                        ->first();
        }  
        
        $empresa_id = Session::get('empresa');
        $valorImpuesto = EMPRESA::select('IMPUESTO')->where('ID', '=', $empresa_id)->first();
        $valorImpuesto = round(($valorImpuesto->IMPUESTO / 100), 4); 
        $impuestoAplicar = SubcategoriaTipoGasto::where('ID', '=', $factura->SUBCATEGORIA_TIPOGASTO_ID)->pluck('MONTO_A_APLICAR');

        if ($detallePresupuesto->FRECUENCIATIEMPO_ID == 2) {
            $fechaInicio = (new Carbon($factura->FECHA_FACTURA))->startOfWeek();
            $fechaFinal = (new Carbon($factura->FECHA_FACTURA))->endOfWeek();
        } 
        if ($detallePresupuesto->FRECUENCIATIEMPO_ID == 4) {
            $fechaInicio = (new Carbon($factura->FECHA_FACTURA))->startOfMonth();
            $fechaFinal = (new Carbon($factura->FECHA_FACTURA))->endOfMonth();
        } 
        if ($detallePresupuesto->FRECUENCIATIEMPO_ID == 5) {
            $fechaInicio = (new Carbon($factura->FECHA_FACTURA))->startOfYear();
            $fechaFinal = (new Carbon($factura->FECHA_FACTURA))->endOfYear();
        } 

        $montoAcumulado = 0;
       

        /** Se obtiene Estado Anulado **/                                    
        $anulado = Factura::where('id', '=', $id)->pluck('anulado');
       
            if ($anulado == 1) {
                Factura::where('id', $id)
                            ->update(['ANULADO' => 0]);
                $anular = 'No';
                echo $anular . '<br>';

                /** Se obtienen facturas del mismo Presupuesto */

        $compartenPresupuesto = Factura::select('ID', 'MONTO_AFECTO', 'MONTO_EXENTO', 'MONTO_IVA', 'CANTIDAD_PORCENTAJE_CUSTOM',
                                                'MONTO_REMANENTE', 'MONTO_CONVERSION', 'APROBACION_PAGO')
                                        ->where('LIQUIDACION_ID', '=', $factura->LIQUIDACION_ID)
                                        ->where('DETPRESUPUESTO_ID', '=', $factura->DETPRESUPUESTO_ID)
                                        ->where('ANULADO', '=', 0)
                                        ->whereBetween('FECHA_FACTURA', [$fechaInicio, $fechaFinal])                                            
->get();


                //Actualiza Lote
                foreach ($compartenPresupuesto as $item) { 
                    $saldo = $detallePresupuesto->MONTO - $montoAcumulado;    
                    echo 'Acumulado: ' . $montoAcumulado . '<br>';                   
                    $fac = calculos($item, $esCombustible, $item->MONTO_CONVERSION, $valorImpuesto, $saldo, $impuestoAplicar);
                    if (isset($esCombustible)) {
                        if ($esCombustible->TIPOASIGNACION_ID == 2) { 
                            $montoAcumulado += $item->CANTIDAD_PORCENTAJE_CUSTOM;
                        } 
                    } else {
                        $montoAcumulado += $item->MONTO_CONVERSION;                                        
                    }
                    Factura::where('ID', '=', $fac->ID)
                                ->update(['MONTO_AFECTO' => $fac->MONTO_AFECTO, 'MONTO_EXENTO' => $fac->MONTO_EXENTO, 'MONTO_IVA' => $fac->MONTO_IVA,
                                          'CANTIDAD_PORCENTAJE_CUSTOM' => $fac->CANTIDAD_PORCENTAJE_CUSTOM, 'MONTO_REMANENTE' => $fac->MONTO_REMANENTE,
                                          'MONTO_CONVERSION' => $fac->MONTO_CONVERSION, 'APROBACION_PAGO' => $fac->APROBACION_PAGO]);
                    echo $fac . '<br>'; 
                }
               
            } else {
                Factura::where('id', $id)
                ->update(['ANULADO' => 1]);            
                $anular = 'Si';
                echo $anular . '<br>';

                $compartenPresupuesto = Factura::select('ID', 'MONTO_AFECTO', 'MONTO_EXENTO', 'MONTO_IVA', 'CANTIDAD_PORCENTAJE_CUSTOM',
                                                'MONTO_REMANENTE', 'MONTO_CONVERSION', 'APROBACION_PAGO')
                                        ->where('LIQUIDACION_ID', '=', $factura->LIQUIDACION_ID)
                                        ->where('DETPRESUPUESTO_ID', '=', $factura->DETPRESUPUESTO_ID)
                                        ->where('ID', '!=', $factura->ID)
                                        ->where('ANULADO', '=', 0)
                                        ->whereBetween('FECHA_FACTURA', [$fechaInicio, $fechaFinal])                                            
                                        ->get();

                //Actualiza Monto              
                foreach ($compartenPresupuesto as $item) { 
                    $saldo = $detallePresupuesto->MONTO - $montoAcumulado;    
                    echo 'Acumulado: ' . $montoAcumulado . '<br>';                   
                    $fac = calculos($item, $esCombustible, $item->MONTO_CONVERSION, $valorImpuesto, $saldo, $impuestoAplicar);
                    if (isset($esCombustible)) {
                        if ($esCombustible->TIPOASIGNACION_ID == 2) { 
                            $montoAcumulado += $item->CANTIDAD_PORCENTAJE_CUSTOM;
                        } 
                    } else {
                        $montoAcumulado += $item->MONTO_CONVERSION;                                        
                    }
                    Factura::where('ID', '=', $fac->ID)
                                ->update(['MONTO_AFECTO' => $fac->MONTO_AFECTO, 'MONTO_EXENTO' => $fac->MONTO_EXENTO, 'MONTO_IVA' => $fac->MONTO_IVA,
                                          'CANTIDAD_PORCENTAJE_CUSTOM' => $fac->CANTIDAD_PORCENTAJE_CUSTOM, 'MONTO_REMANENTE' => $fac->MONTO_REMANENTE,
                                          'MONTO_CONVERSION' => $fac->MONTO_CONVERSION, 'APROBACION_PAGO' => $fac->APROBACION_PAGO]);
                    echo $fac . '<br>';
                }

            }
            //dd('alto');
            //dd($compartenPresupuesto);
            return $anular;  
        
    }

     /**
     * Remove the specified resource from send SAP.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function anularFacturaSAP($id)
    {
        $anuladoEnvioSAP = Factura::where('id', '=', $id)->pluck('ANULARENVIO_SAP');        
       
            if ($anuladoEnvioSAP == 1) {
                Factura::where('id', $id)
                            ->update(['ANULARENVIO_SAP' => 0]);
                $anularEnvioSAP = 'No';
            } else {                
                Factura::where('id', $id)
                ->update(['ANULARENVIO_SAP' => 1]);            
                $anularEnvioSAP = 'Si';
            }  
            return $anularEnvioSAP;  
        
    }
}

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
   /* $tipoGasto = DB::select("select tg.DESCRIPCION, tg.ID
                                  from liq_liquidacion as l inner join pre_presupuesto as p on p.USUARIORUTA_ID = l.USUARIORUTA_ID
                                                               inner join pre_detpresupuesto as dp on dp.PRESUPUESTO_ID = p.ID
                                                               inner join cat_tipogasto as tg on tg.ID = dp.TIPOGASTO_ID
                                                               where l.FECHA_INICIO = p.VIGENCIA_INICIO and
                                                                     l.FECHA_FINAL = p.VIGENCIA_FINAL and
                                                                     l.ID = $liquidacion_id");
         dd($tipoGasto[0],[1]);
dd($resultado);
*/
         $subcategoria = null;//SubcategoriaTipoGasto::where('ANULADO', '=', 0)->lists('DESCRIPCION', 'ID')->toArray();
         $fechas =  Liquidacion::select('liq_liquidacion.FECHA_INICIO', 'liq_liquidacion.FECHA_FINAL', 'USUARIORUTA_ID' )
                                        ->where('liq_liquidacion.ID', '=', $liquidacion_id)
                                        ->first();


         $fechaInicio = $fechas->FECHA_INICIO;
         $fechaFinal = $fechas->FECHA_FINAL;

         $ruta = UsuarioRuta::select('cat_ruta.DESCRIPCION')
                                    ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                    ->where('cat_usuarioruta.ID', '=', $fechas->USUARIORUTA_ID)
                                    ->first();
                  

        /*** Tipo de Gasto Permitdos en Presupuesto ***/
        
        if(($tipoLiquidacion == 'Otros Gastos') && ($ruta->DESCRIPCION == 'Depreciación') || ($ruta->DESCRIPCION == 'Depreciacion') )  {
            //dd('ahi vamos');
            $tipoGasto =  //Liquidacion::join('pre_presupuesto', 'pre_presupuesto.USUARIORUTA_ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
            //->join('pre_detpresupuesto', 'pre_detpresupuesto.PRESUPUESTO_ID', '=', 'pre_presupuesto.ID' )
            //->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'pre_detpresupuesto.TIPOGASTO_ID')
            //->whereDate('pre_presupuesto.VIGENCIA_INICIO', '=', $fechaInicio)
            //->whereDate('pre_presupuesto.VIGENCIA_FINAL', '=', $fechaFinal)
            TipoGasto::where('CONTROL_DEPRECIACION', '=', 1)
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
//dd('punto de control');
         

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

         $empresa_id = Session::get('empresa');
         

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
        
        if($request->TIPO_LIQUIDACION == 'Rutas') {            
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
        
        /**  Se valida si es combustible que se ingrese la cantidad correspondiente de galones y que Km Final sea > Km Inicial**/
        if ($request->CATEGORIA_GASTO == 'combustible') {
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
        
        $factura = new Factura();
        if (trim($request->FMONEDA_ID) == 'USD') { 
            $montoConversion = round(($request->TOTAL * $request->TASA_CAMBIO), 4);           
        } else {
            $montoConversion = $request->TOTAL * 1;
        }

        /** Se valida que factura sea ingresada una sola vez */
        $datosFactura = Factura::select('PROVEEDOR_ID', 'SERIE', 'NUMERO')->get();
        //dd($request->all());
        foreach ($datosFactura as $datos) {
           /*  echo $datos->PROVEEDOR_ID . '<br>';
            echo $datos->SERIE . '<br>';
            echo $datos->NUMERO . '<br>'; */
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

        /** Se Determina si es Liquidación de Depreciación */

        $nombreRuta = Liquidacion::join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                    ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                    ->where('liq_liquidacion.ID', '=', $request->LIQUIDACION_ID)
                                    ->select('cat_ruta.DESCRIPCION')->first();
                                            
        /** Se obtiene No. de Detalle Presupuesto al que Corresponde **/

        if(($request->TIPO_LIQUIDACION == 'Otros Gastos') && ((strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACIóN') || strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACION') ) {            
            $detallePresupuesto = Presupuesto::select('ID', 'ASIGNACION_MENSUAL as MONTO')->where('ID', '=', $request->PRESUPUESTO_ID)->first();
            $frecuenciaPresupuesto = 4;
            //dd('ahi vamos...' . $detallePresupuesto->MONTO);
        } else {            
            $detallePresupuesto = DetallePresupuesto::select('ID', 'MONTO')
                                                        ->where('PRESUPUESTO_ID', '=', $request->PRESUPUESTO_ID)
                                                        ->where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                                                        ->first();
            $frecuenciaPresupuesto = DetallePresupuesto::where('ID', '=', $detallePresupuesto->ID)->pluck('FRECUENCIATIEMPO_ID');
        }
          
        
       /*  $numeroSemana = (new Carbon($request->FECHA_FACTURA))->weekOfYear;       
        $inicioSemana = (new Carbon($request->FECHA_FACTURA))->startOfWeek();
        $finSemana = (new Carbon($request->FECHA_FACTURA))->endOfWeek();    */   

        
        
        /** Validación de Frecuencia de Periodos */       
        if($frecuenciaPresupuesto == 1) { 
            /** Se obtiene monto gastado hasta el momento por tipo de gasto **/
            if ($request->CATEGORIA_GASTO == 'combustible' && $request->TIPO_LIQUIDACION != 'Otros Gastos' ) {                      ;
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)
                where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->where('ANULADO', '=', 0)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                ->sum('CANTIDAD_PORCENTAJE_CUSTOM');
                
            } elseif (strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACIóN' || strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACION') {            
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)            
                whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                                            ->where('ANULADO', '=', 0)
                                            ->sum('TOTAL');
            } else {             
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                ->where('ANULADO', '=', 0)
                ->sum('TOTAL');                        
            }
        } elseif ($frecuenciaPresupuesto == 2) { 
            /** Se obtiene monto gastado hasta el momento por tipo de gasto **/
            if ($request->CATEGORIA_GASTO == 'combustible' && $request->TIPO_LIQUIDACION != 'Otros Gastos' ) {       
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)
                where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->where('ANULADO', '=', 0)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                ->sum('CANTIDAD_PORCENTAJE_CUSTOM');
                
            } elseif (strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACIóN' || strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACION') {            
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)            
                whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                                            ->where('ANULADO', '=', 0)
                                            ->sum('TOTAL');
            } else {                            
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                ->where('ANULADO', '=', 0)
                ->sum('TOTAL');                        
            }
        } elseif ($frecuenciaPresupuesto == 4) {
            /** Se obtiene monto gastado hasta el momento por tipo de gasto **/
            if ($request->CATEGORIA_GASTO == 'combustible' && $request->TIPO_LIQUIDACION != 'Otros Gastos' ) {                      ;
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)
                where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->where('ANULADO', '=', 0)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfMonth(), (new Carbon($request->FECHA_FACTURA))->endOfMonth()])
                ->sum('CANTIDAD_PORCENTAJE_CUSTOM');
                
            } elseif (strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACIóN' || strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACION') {            
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)            
                whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfMonth(), (new Carbon($request->FECHA_FACTURA))->endOfMonth()])
                                            ->where('ANULADO', '=', 0)
                                            ->sum('TOTAL');
            } else {             
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfMonth(), (new Carbon($request->FECHA_FACTURA))->endOfMonth()])
                ->where('ANULADO', '=', 0)
                ->sum('TOTAL');                        
            }
        } elseif ($frecuenciaPresupuesto == 5) {
            /** Se obtiene monto gastado hasta el momento por tipo de gasto **/
            if ($request->CATEGORIA_GASTO == 'combustible' && $request->TIPO_LIQUIDACION != 'Otros Gastos' ) {                      ;
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)
                where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->where('ANULADO', '=', 0)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfYear(), (new Carbon($request->FECHA_FACTURA))->endOfYear()])
                ->sum('CANTIDAD_PORCENTAJE_CUSTOM');
                
            } elseif (strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACIóN' || strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACION') {            
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)            
                whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfYear(), (new Carbon($request->FECHA_FACTURA))->endOfYear()])
                                            ->where('ANULADO', '=', 0)
                                            ->sum('TOTAL');
            } else {             
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfYear(), (new Carbon($request->FECHA_FACTURA))->endOfYear()])
                ->where('ANULADO', '=', 0)
                ->sum('TOTAL');                        
            }
        }   
                
        $saldo = $detallePresupuesto->MONTO - $montoAcumulado;        
        
        //Se determina si tiene presupuesto para cubrir el gasto o si existe remanente

        if ($request->CATEGORIA_GASTO == 'combustible' && $request->TIPO_LIQUIDACION != 'Otros Gastos' ) {
            
            $idp = SubcategoriaTipoGasto::select('MONTO_A_APLICAR')->where('ID', '=', $request->subcategoriaTipoGasto)->first();
            
            if ($saldo > 0) {
                $saldoFactura = $saldo - $request->CANTIDAD_PORCENTAJE_CUSTOM;
                
                if ($saldoFactura > 0) {
                    $factura->APROBACION_PAGO = 1;
    
                    /** Operaciones de Calculo **/
                    $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 4);
                    
                    $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                    
                    $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto), 4); //Se calcula monto de impuesto
                   
                } else {
                    
                    $saldoParcial = $saldo;
                    
                    $remanente = $request->CANTIDAD_PORCENTAJE_CUSTOM - $saldoParcial; //Ojo aca puede ser util                

                    $precioGalon = round(($montoConversion / $request->CANTIDAD_PORCENTAJE_CUSTOM), 4);
                    
                    $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 4);
                    
                    $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                    
                    $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto

                    //$factura->MONTO_EXENTO = $reembolsable * $precioGalon *  
                    $factura->MONTO_REMANENTE = round(($remanente * $precioGalon), 4);    
                    $factura->APROBACION_PAGO = 1;
                    
                }
    
            } else {            
                $factura->APROBACION_PAGO = 0;
                
                /** Operaciónes de Calculo **/
                $factura->MONTO_REMANENTE = $montoConversion;
                $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 4);
                    
                $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                
                $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto), 4); //Se calcula monto de impuesto            
            }
            
        /* } else if (strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACIóN' || strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACION') { */
        } else if ($request->CATEGORIA_GASTO == 'combustible' && $request->TIPO_LIQUIDACION != 'Rutas' ) {
            //dd('bien SAFIRO!!!');
            $idp = SubcategoriaTipoGasto::select('MONTO_A_APLICAR')->where('ID', '=', $request->subcategoriaTipoGasto)->first();
            
            if ($saldo > 0) {
                $saldoFactura = $saldo - $montoConversion;
                
                if ($saldoFactura > 0) {
                    $factura->APROBACION_PAGO = 1;
    
                    /** Operaciones de Calculo **/
                    $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 4);
                    
                    $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                    
                    $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto
                   
                } else {                  
                    
                    $saldoParcial = $saldo;
                    
                    $remanente = $montoConversion - $saldoParcial; //Ojo aca puede ser util                

                    //$precioGalon = round(($montoConversion / $request->CANTIDAD_PORCENTAJE_CUSTOM), 4);

                    $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 4);
                    
                    $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                    
                    $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto

                    //$factura->MONTO_EXENTO = $reembolsable * $precioGalon *  
                    //$factura->MONTO_REMANENTE = round(($remanente * $precioGalon), 4);    
                    $factura->MONTO_REMANENTE = round($remanente, 4);
                    $factura->APROBACION_PAGO = 1;                    
                }
    
            } else {            
                $factura->APROBACION_PAGO = 0;
                
                /** Operaciónes de Calculo **/
                $factura->MONTO_REMANENTE = $montoConversion;      
                $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 4);
                    
                $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                
                $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto      
            }
        } else {           
            $findMe = 'con';
            
            $impuestoHotel = strpos(strtolower($request->SUBCATEGORIA_GASTO), $findMe); 
            
            if($impuestoHotel !== false)            
            {   
                $impuesto = SubcategoriaTipoGasto::select('MONTO_A_APLICAR')->where('ID', '=', $request->subcategoriaTipoGasto)->first();
                $impuestoInguat = round(($impuesto->MONTO_A_APLICAR / 100), 4);      
                
                
                $factura->CANTIDAD_PORCENTAJE_CUSTOM = $impuestoInguat;
                if ($saldo > 0) {
                    $saldoFactura = $saldo - $montoConversion;
                    
                    if ($saldoFactura > 0) {
                        $factura->APROBACION_PAGO = 1;
        
                        /** Operaciones de Calculo **/                                               
                        $factura->MONTO_AFECTO = round((($montoConversion) / (1 + $valorImpuesto + $impuestoInguat)),4); //Se calcula monto afecto
                        $factura->MONTO_EXENTO = round(($factura->MONTO_AFECTO * $impuestoInguat), 4);
                        
                        
                        $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto
                        
                        
                    } else {
                        
                        $saldoParcial = $saldo;
                        
                        $remanente = $montoConversion - $saldoParcial; //Ojo aca puede ser util
    
                        $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto + $impuestoInguat)),4); //Se calcula monto afecto
                        
                        $factura->MONTO_EXENTO = round(($factura->MONTO_AFECTO * $impuestoInguat), 4);
                        
                        $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto

                        $factura->MONTO_REMANENTE = round($remanente, 4);
                            
                        $factura->APROBACION_PAGO = 1;
                        
                    }
        
                } else {            
                    $factura->APROBACION_PAGO = 0;
                    
                    /** Operaciónes de Calculo **/
                    $factura->MONTO_REMANENTE = $montoConversion; 
                    $factura->MONTO_AFECTO = round((($montoConversion) / (1 + $valorImpuesto + $impuestoInguat)),4); //Se calcula monto afecto                    
                    $factura->MONTO_EXENTO = round(($factura->MONTO_AFECTO * $impuestoInguat), 4);   
                    $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4);        
                    
                }                

                
            } else {                     
                if ($saldo > 0) { 
                    $saldoFactura = $saldo - $montoConversion;
                    if ($saldoFactura > 0) {                        
                        $factura->APROBACION_PAGO = 1;
        
                        /** Operaciones de Calculo **/
        
                        $factura->MONTO_AFECTO = round(($montoConversion / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                
                        $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto
        
                    } else {                        
                        $saldoParcial = $saldo;

                        $factura->MONTO_AFECTO = round(($montoConversion / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                        
                        $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto), 4); //Se calcula monto de impuesto
                        $factura->MONTO_EXENTO = 0;
                        
                        $factura->MONTO_REMANENTE = $montoConversion - $saldoParcial;                
                        
                        $factura->APROBACION_PAGO = 1;
                    }
        
                } else {            
                    $factura->APROBACION_PAGO = 0;
                
                    /** Operaciónes de Calculo **/
                    $factura->MONTO_AFECTO = round(($montoConversion / (1 + $valorImpuesto)),4); //Se calcula monto afecto                
                    $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto
                    $factura->MONTO_REMANENTE = $montoConversion;            
                }

            }
            
        }
        

        if ($request->CANTIDAD_PORCENTAJE_CUSTOM == '') {
            $request->CANTIDAD_PORCENTAJE_CUSTOM = 0.00;
        } 
        if($request->TASA_CAMBIO == '') {            
            $request->TASA_CAMBIO = 1.00;
        }

        //dd('Este es el Pago Parcial: ' . $saldoParcial . ' y este el Remanente: ' . $remanente);

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

//dd($factura);
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

        $empresa = Session::get('loginEmpresa');
        
        if($request->TIPO_LIQUIDACION == 'Rutas') {            
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

        /**  Se valida si es combustible que se ingrese la cantidad correspondiente de galones y que Km Final sea > Km Inicial**/
        if ($request->CATEGORIA_GASTO == 'combustible') {
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


        $factura = Factura::findOrFail($id);

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
               /*  echo $datos->PROVEEDOR_ID . '<br>';
                echo $datos->SERIE . '<br>';
                echo $datos->NUMERO . '<br>'; */
                if(($datos->PROVEEDOR_ID == $request->PROVEEDOR_ID) && ($datos->SERIE == $request->SERIE) && ($datos->NUMERO == $request->NUMERO) ) {
                    return back()->withInput()->with('info', 'La Factura Ya existe en la Base de Datos!!');
                }
            }
        } 

        /** Se obtiene valor de impuesto */
        $empresa_id = Session::get('empresa');
        $valorImpuesto = EMPRESA::select('IMPUESTO')->where('ID', '=', $empresa_id)->first();
        $valorImpuesto = round(($valorImpuesto->IMPUESTO / 100), 4); 
        
        /** Se Determina si es Liquidación de Depreciación */

        $nombreRuta = Liquidacion::join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                    ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                    ->where('liq_liquidacion.ID', '=', $request->LIQUIDACION_ID)
                                    ->select('cat_ruta.DESCRIPCION')->first();
               
        
        /** Se obtiene No. de Detalle Presupuesto al que Corresponde **/
        
        if(($request->TIPO_LIQUIDACION == 'Otros Gastos') && ((strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACIóN') || strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACION') ) {            
            $detallePresupuesto = Presupuesto::select('ID', 'ASIGNACION_MENSUAL as MONTO')->where('ID', '=', $request->PRESUPUESTO_ID)->first();
            $frecuenciaPresupuesto = 4;
            //dd('ahi vamos...' . $detallePresupuesto->MONTO);
        } else {            
            $detallePresupuesto = DetallePresupuesto::select('ID', 'MONTO')
                                                        ->where('PRESUPUESTO_ID', '=', $request->PRESUPUESTO_ID)
                                                        ->where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                                                        ->first();
            $frecuenciaPresupuesto = DetallePresupuesto::where('ID', '=', $detallePresupuesto->ID)->pluck('FRECUENCIATIEMPO_ID');
        }
                       
               
        
         /** Validación de Frecuencia de Periodos */       
         if($frecuenciaPresupuesto == 1) { 
            /** Se obtiene monto gastado hasta el momento por tipo de gasto **/
            if ($request->CATEGORIA_GASTO == 'combustible' && $request->TIPO_LIQUIDACION != 'Otros Gastos' ) {                      ;
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)
                where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->where('ANULADO', '=', 0)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                ->sum('CANTIDAD_PORCENTAJE_CUSTOM');
                
            } elseif (strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACIóN' || strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACION') {            
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)            
                whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                                            ->where('ANULADO', '=', 0)
                                            ->sum('TOTAL');
            } else {             
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                ->where('ANULADO', '=', 0)
                ->sum('TOTAL');                        
            }
        } elseif ($frecuenciaPresupuesto == 2) { 
            /** Se obtiene monto gastado hasta el momento por tipo de gasto **/
            if ($request->CATEGORIA_GASTO == 'combustible' && $request->TIPO_LIQUIDACION != 'Otros Gastos' ) {       
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)
                where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->where('ANULADO', '=', 0)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                ->sum('CANTIDAD_PORCENTAJE_CUSTOM');
                
            } elseif (strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACIóN' || strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACION') {            
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)            
                whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                                            ->where('ANULADO', '=', 0)
                                            ->sum('TOTAL');
            } else {                            
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfWeek(), (new Carbon($request->FECHA_FACTURA))->endOfWeek()])
                ->where('ANULADO', '=', 0)
                ->sum('TOTAL');                        
            }
        } elseif ($frecuenciaPresupuesto == 4) {
            /** Se obtiene monto gastado hasta el momento por tipo de gasto **/
            if ($request->CATEGORIA_GASTO == 'combustible' && $request->TIPO_LIQUIDACION != 'Otros Gastos' ) {                      ;
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)
                where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->where('ANULADO', '=', 0)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfMonth(), (new Carbon($request->FECHA_FACTURA))->endOfMonth()])
                ->sum('CANTIDAD_PORCENTAJE_CUSTOM');
                
            } elseif (strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACIóN' || strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACION') {            
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)            
                whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfMonth(), (new Carbon($request->FECHA_FACTURA))->endOfMonth()])
                                            ->where('ANULADO', '=', 0)
                                            ->sum('TOTAL');
            } else {             
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfMonth(), (new Carbon($request->FECHA_FACTURA))->endOfMonth()])
                ->where('ANULADO', '=', 0)
                ->sum('TOTAL');                        
            }
        } elseif ($frecuenciaPresupuesto == 5) {
            /** Se obtiene monto gastado hasta el momento por tipo de gasto **/
            if ($request->CATEGORIA_GASTO == 'combustible' && $request->TIPO_LIQUIDACION != 'Otros Gastos' ) {                      ;
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)
                where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->where('ANULADO', '=', 0)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfYear(), (new Carbon($request->FECHA_FACTURA))->endOfYear()])
                ->sum('CANTIDAD_PORCENTAJE_CUSTOM');
                
            } elseif (strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACIóN' || strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACION') {            
                $montoAcumulado = Factura:://where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)            
                whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfYear(), (new Carbon($request->FECHA_FACTURA))->endOfYear()])
                                            ->where('ANULADO', '=', 0)
                                            ->sum('TOTAL');
            } else {             
                $montoAcumulado = Factura::where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                ->whereBetween('FECHA_FACTURA', [(new Carbon($request->FECHA_FACTURA))->startOfYear(), (new Carbon($request->FECHA_FACTURA))->endOfYear()])
                ->where('ANULADO', '=', 0)
                ->sum('TOTAL');                        
            }
        }
                //dd($factura->APROBACION_PAGO);
                //dd('Presupuesto: ' . $detallePresupuesto->MONTO . ' Y el monto acumulado es :' . $montoAcumulado . ' y factura: ' . $factura->TOTAL);
                //$montoAcumulado -= $factura->TOTAL;
                //dd($detallePresupuesto->MONTO);
                $montoAcumulado -= $factura->TOTAL; // Se resta valor actual de la factura
                //dd($montoAcumulado);
                
                $saldo = $detallePresupuesto->MONTO - $montoAcumulado;        
                //dd($saldo);

                //Se determina si tiene presupuesto para cubrir el gasto o si existe remanente
        
                if ($request->CATEGORIA_GASTO == 'combustible' && $request->TIPO_LIQUIDACION != 'Otros Gastos' ) {
            
                    $idp = SubcategoriaTipoGasto::select('MONTO_A_APLICAR')->where('ID', '=', $request->subcategoriaTipoGasto)->first();
                    
                    if ($saldo > 0) {
                        $saldoFactura = $saldo - $request->CANTIDAD_PORCENTAJE_CUSTOM;
                        
                        if ($saldoFactura > 0) {
                            $factura->APROBACION_PAGO = 1;
            
                            /** Operaciones de Calculo **/
                            $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 4);
                            
                            $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                            
                            $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto), 4); //Se calcula monto de impuesto
                           
                        } else {
                            
                            $saldoParcial = $saldo;
                            
                            $remanente = $request->CANTIDAD_PORCENTAJE_CUSTOM - $saldoParcial; //Ojo aca puede ser util                
        
                            $precioGalon = round(($montoConversion / $request->CANTIDAD_PORCENTAJE_CUSTOM), 4);
                            
                            $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 4);
                            
                            $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                            
                            $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto
        
                            //$factura->MONTO_EXENTO = $reembolsable * $precioGalon *  
                            $factura->MONTO_REMANENTE = round(($remanente * $precioGalon), 4);    
                            $factura->APROBACION_PAGO = 1;
                            
                        }
            
                    } else {            
                        $factura->APROBACION_PAGO = 0;
                        
                        /** Operaciónes de Calculo **/
                        $factura->MONTO_REMANENTE = $montoConversion;
                        $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 4);
                            
                        $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                        
                        $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto), 4); //Se calcula monto de impuesto            
                    }
                    
                /* } else if (strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACIóN' || strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACION') { */
                } else if ($request->CATEGORIA_GASTO == 'combustible' && $request->TIPO_LIQUIDACION != 'Rutas' ) {
                    //dd('bien SAFIRO!!!');
                    $idp = SubcategoriaTipoGasto::select('MONTO_A_APLICAR')->where('ID', '=', $request->subcategoriaTipoGasto)->first();
                    
                    if ($saldo > 0) {
                        $saldoFactura = $saldo - $montoConversion;
                        
                        if ($saldoFactura > 0) {
                            $factura->APROBACION_PAGO = 1;
            
                            /** Operaciones de Calculo **/
                            $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 4);
                            
                            $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                            
                            $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto
                           
                        } else {                  
                            
                            $saldoParcial = $saldo;
                            
                            $remanente = $montoConversion - $saldoParcial; //Ojo aca puede ser util                
        
                            //$precioGalon = round(($montoConversion / $request->CANTIDAD_PORCENTAJE_CUSTOM), 4);
        
                            $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 4);
                            
                            $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                            
                            $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto
        
                            //$factura->MONTO_EXENTO = $reembolsable * $precioGalon *  
                            //$factura->MONTO_REMANENTE = round(($remanente * $precioGalon), 4);    
                            $factura->MONTO_REMANENTE = round($remanente, 4);
                            $factura->APROBACION_PAGO = 1;                    
                        }
            
                    } else {            
                        $factura->APROBACION_PAGO = 0;
                        
                        /** Operaciónes de Calculo **/
                        $factura->MONTO_REMANENTE = $montoConversion;      
                        $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 4);
                            
                        $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                        
                        $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto      
                    }
                } else {           
                    $findMe = 'con';
                    
                    $impuestoHotel = strpos(strtolower($request->SUBCATEGORIA_GASTO), $findMe); 
                    
                    if($impuestoHotel !== false)            
                    {   
                        $impuesto = SubcategoriaTipoGasto::select('MONTO_A_APLICAR')->where('ID', '=', $request->subcategoriaTipoGasto)->first();
                        $impuestoInguat = round(($impuesto->MONTO_A_APLICAR / 100), 4);      
                        
                        
                        $factura->CANTIDAD_PORCENTAJE_CUSTOM = $impuestoInguat;
                        if ($saldo > 0) {
                            $saldoFactura = $saldo - $montoConversion;
                            
                            if ($saldoFactura > 0) {
                                $factura->APROBACION_PAGO = 1;
                
                                /** Operaciones de Calculo **/                                               
                                $factura->MONTO_AFECTO = round((($montoConversion) / (1 + $valorImpuesto + $impuestoInguat)),4); //Se calcula monto afecto
                                $factura->MONTO_EXENTO = round(($factura->MONTO_AFECTO * $impuestoInguat), 4);
                                
                                
                                $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto
                                
                                
                            } else {
                                
                                $saldoParcial = $saldo;
                                
                                $remanente = $montoConversion - $saldoParcial; //Ojo aca puede ser util
            
                                $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + $valorImpuesto + $impuestoInguat)),4); //Se calcula monto afecto
                                
                                $factura->MONTO_EXENTO = round(($factura->MONTO_AFECTO * $impuestoInguat), 4);
                                
                                $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto
        
                                $factura->MONTO_REMANENTE = round($remanente, 4);
                                    
                                $factura->APROBACION_PAGO = 1;
                                
                            }
                
                        } else {            
                            $factura->APROBACION_PAGO = 0;
                            
                            /** Operaciónes de Calculo **/
                            $factura->MONTO_REMANENTE = $montoConversion; 
                            $factura->MONTO_AFECTO = round((($montoConversion) / (1 + $valorImpuesto + $impuestoInguat)),4); //Se calcula monto afecto                    
                            $factura->MONTO_EXENTO = round(($factura->MONTO_AFECTO * $impuestoInguat), 4);   
                            $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4);        
                            
                        }                
        
                        
                    } else {                     
                        if ($saldo > 0) { 
                            $saldoFactura = $saldo - $montoConversion;
                            if ($saldoFactura > 0) {                        
                                $factura->APROBACION_PAGO = 1;
                
                                /** Operaciones de Calculo **/
                
                                $factura->MONTO_AFECTO = round(($montoConversion / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                        
                                $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto
                
                            } else {                        
                                $saldoParcial = $saldo;
        
                                $factura->MONTO_AFECTO = round(($montoConversion / (1 + $valorImpuesto)),4); //Se calcula monto afecto
                                
                                $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto), 4); //Se calcula monto de impuesto
                                $factura->MONTO_EXENTO = 0;
                                
                                $factura->MONTO_REMANENTE = $montoConversion - $saldoParcial;                
                                
                                $factura->APROBACION_PAGO = 1;
                            }
                
                        } else {            
                            $factura->APROBACION_PAGO = 0;
                        
                            /** Operaciónes de Calculo **/
                            $factura->MONTO_AFECTO = round(($montoConversion / (1 + $valorImpuesto)),4); //Se calcula monto afecto                
                            $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * $valorImpuesto ), 4); //Se calcula monto de impuesto
                            $factura->MONTO_REMANENTE = $montoConversion;            
                        }
        
                    }
                    
                }
                
        
                if ($request->CANTIDAD_PORCENTAJE_CUSTOM == '') {
                    $request->CANTIDAD_PORCENTAJE_CUSTOM = 0.00;
                } 
        
        Factura::where('ID', $id)
                ->update(['TIPOGASTO_ID' => $request->TIPOGASTO_ID, 'MONEDA_ID' => $request->FMONEDA_ID, 'PROVEEDOR_ID' => $request->PROVEEDOR_ID, 
                          'KILOMETRAJE_INICIAL' => $request->KM_INICIO, 'KILOMETRAJE_FINAL' => $request->KM_FINAL, 'CORRECCION' => 0, 'SUBCATEGORIA_TIPOGASTO_ID' => $request->subcategoriaTipoGasto,
                          'SERIE' => $request->SERIE, 'NUMERO' => $request->NUMERO, 'FECHA_FACTURA' => $request->FECHA_FACTURA, 'TOTAL' => $montoConversion, 
                          'CANTIDAD_PORCENTAJE_CUSTOM' => $request->CANTIDAD_PORCENTAJE_CUSTOM, 'COMENTARIO_PAGO' => $request->COMENTARIO_PAGO, 
                          'APROBACION_PAGO' => $factura->APROBACION_PAGO, 'MONTO_AFECTO' => $factura->MONTO_AFECTO, 'MONTO_EXENTO' => $factura->MONTO_EXENTO,
                          'MONTO_IVA' => $factura->MONTO_IVA, 'MONTO_REMANENTE' => $factura->MONTO_REMANENTE, 'TIPODOCUMENTO_ID' => $request->TIPODOCUMENTO_ID,]);
                          

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
        //dd('por aca voy!');
        $anulado = Factura::where('id', '=', $id)->pluck('anulado');
       
            if ($anulado == 1) {
                Factura::where('id', $id)
                            ->update(['ANULADO' => 0]);
                $anular = 'No';

                //Actualiza Monto Remanente
                
                $facturaActual = Factura::where('id', '=',  $id)->select('LIQUIDACION_ID', 'DETPRESUPUESTO_ID', 'TOTAL', 'FECHA_FACTURA')->first();

                $periodoPresupuesto = DetallePresupuesto::select('MONTO', 'FRECUENCIATIEMPO_ID')
                ->where('ID', '=', $facturaActual->DETPRESUPUESTO_ID)
                ->first();

                if ($periodoPresupuesto->FRECUENCIATIEMPO_ID == 2) {
                    $fechaInicio = (new Carbon($facturaActual->FECHA_FACTURA))->startOfWeek();
                    $fechaFinal = (new Carbon($facturaActual->FECHA_FACTURA))->endOfWeek();
                } 
                if ($periodoPresupuesto->FRECUENCIATIEMPO_ID == 4) {
                    $fechaInicio = (new Carbon($facturaActual->FECHA_FACTURA))->startOfMonth();
                    $fechaFinal = (new Carbon($facturaActual->FECHA_FACTURA))->endOfMonth();
                } 
                if ($periodoPresupuesto->FRECUENCIATIEMPO_ID == 5) {
                    $fechaInicio = (new Carbon($facturaActual->FECHA_FACTURA))->startOfYear();
                    $fechaFinal = (new Carbon($facturaActual->FECHA_FACTURA))->endOfYear();
                } 

                $compartenPresupuesto = Factura::select('ID', 'MONTO_REMANENTE', 'TOTAL', 'APROBACION_PAGO')
                                                    ->where('LIQUIDACION_ID', '=', $facturaActual->LIQUIDACION_ID)
                                                    ->where('DETPRESUPUESTO_ID', '=', $facturaActual->DETPRESUPUESTO_ID)
                                                    ->where('ANULADO', '=', 0)
                                                    ->whereBetween('FECHA_FACTURA', [$fechaInicio, $fechaFinal])
                                                    //->where('ID', '!=', $id)
                                                    ->get();
                
                $montoPresupuesto = $periodoPresupuesto->MONTO;
                $montoAcumulado = 0;
                
                foreach ($compartenPresupuesto as $item) {
                        
                        echo 'Monto Acumulado: ' . $montoAcumulado . '<br>';
                        echo 'Monto Presupuesto: ' . $montoPresupuesto . '<br>';
                        $saldo = $montoPresupuesto - $montoAcumulado;
                        echo 'Saldo: ' . $saldo . '<br>';
                        if ($saldo > 0) {
                            $saldoFactura = $saldo - $item->TOTAL;
                            echo 'Saldo Factura: ' . $saldoFactura . '<br>';
                            if ($saldoFactura > 0) {
                                $item->APROBACION_PAGO = 1;
                                $item->MONTO_REMANENTE = 0;                        
                            } else {
                                $saldoParcial = $saldo;
                                $item->MONTO_REMANENTE = $item->TOTAL - $saldoParcial; 
                                $item->APROBACION_PAGO = 1;
                            }
                        } else {
                            $item->MONTO_REMANENTE = $item->TOTAL;
                            $item->APROBACION_PAGO = 0;                            
                        }
                        echo 'Monto Remanente: ' . $item->MONTO_REMANENTE . '<br>';
                        echo 'Aprobación Factura: ' . $item->APROBACION_PAGO . '<br>';
                        echo 'ID : ' . $item->ID . '<br>';
                        $montoAcumulado += $item->TOTAL;
                        Factura::where('ID', '=', $item->ID)
                        ->update(['MONTO_REMANENTE' => $item->MONTO_REMANENTE, 'APROBACION_PAGO' => $item->APROBACION_PAGO ]);
                    }
            } else {
                Factura::where('id', $id)
                ->update(['ANULADO' => 1]);            
                $anular = 'Si';

                //Actualiza Monto Remanente
                
                $facturaActual = Factura::where('id', '=',  $id)->select('LIQUIDACION_ID', 'DETPRESUPUESTO_ID', 'TOTAL', 'FECHA_FACTURA')->first();

                $periodoPresupuesto = DetallePresupuesto::select('MONTO', 'FRECUENCIATIEMPO_ID')
                ->where('ID', '=', $facturaActual->DETPRESUPUESTO_ID)
                ->first();

                if ($periodoPresupuesto->FRECUENCIATIEMPO_ID == 2) {
                    $fechaInicio = (new Carbon($facturaActual->FECHA_FACTURA))->startOfWeek();
                    $fechaFinal = (new Carbon($facturaActual->FECHA_FACTURA))->endOfWeek();
                } 
                if ($periodoPresupuesto->FRECUENCIATIEMPO_ID == 4) {
                    $fechaInicio = (new Carbon($facturaActual->FECHA_FACTURA))->startOfMonth();
                    $fechaFinal = (new Carbon($facturaActual->FECHA_FACTURA))->endOfMonth();
                } 
                if ($periodoPresupuesto->FRECUENCIATIEMPO_ID == 5) {
                    $fechaInicio = (new Carbon($facturaActual->FECHA_FACTURA))->startOfYear();
                    $fechaFinal = (new Carbon($facturaActual->FECHA_FACTURA))->endOfYear();
                } 

                $compartenPresupuesto = Factura::select('ID', 'MONTO_REMANENTE', 'TOTAL', 'APROBACION_PAGO')
                                                    ->where('LIQUIDACION_ID', '=', $facturaActual->LIQUIDACION_ID)
                                                    ->where('DETPRESUPUESTO_ID', '=', $facturaActual->DETPRESUPUESTO_ID)
                                                    ->where('ANULADO', '=', 0)
                                                    ->whereBetween('FECHA_FACTURA', [$fechaInicio, $fechaFinal])
                                                    ->where('ID', '!=', $id)
                                                    ->get();
                
                $montoPresupuesto = $periodoPresupuesto->MONTO;
                $montoAcumulado = 0;
                
                foreach ($compartenPresupuesto as $item) {
                        
                        echo 'Monto Acumulado: ' . $montoAcumulado . '<br>';
                        echo 'Monto Presupuesto: ' . $montoPresupuesto . '<br>';
                        $saldo = $montoPresupuesto - $montoAcumulado;
                        echo 'Saldo: ' . $saldo . '<br>';
                        if ($saldo > 0) {
                            $saldoFactura = $saldo - $item->TOTAL;
                            echo 'Saldo Factura: ' . $saldoFactura . '<br>';
                            if ($saldoFactura > 0) {
                                $item->APROBACION_PAGO = 1;
                                $item->MONTO_REMANENTE = 0;                        
                            } else {
                                $saldoParcial = $saldo;
                                $item->MONTO_REMANENTE = $item->TOTAL - $saldoParcial; 
                                $item->APROBACION_PAGO = 1;
                            }
                        } else {
                            $item->MONTO_REMANENTE = $item->TOTAL;
                            $item->APROBACION_PAGO = 0;                            
                        }
                        echo 'Monto Remanente: ' . $item->MONTO_REMANENTE . '<br>';
                        echo 'Aprobación Factura: ' . $item->APROBACION_PAGO . '<br>';
                        echo 'ID : ' . $item->ID . '<br>';
                        $montoAcumulado += $item->TOTAL;
                        Factura::where('ID', '=', $item->ID)
                        ->update(['MONTO_REMANENTE' => $item->MONTO_REMANENTE, 'APROBACION_PAGO' => $item->APROBACION_PAGO ]);
                } 

            }
            
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

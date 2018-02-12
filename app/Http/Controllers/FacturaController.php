<?php

namespace App\Http\Controllers;

use App\DetallePresupuesto;
use App\Empresa;
use App\SubcategoriaTipoGasto;
use App\TipoDocumento;
use App\TipoProveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateFacturaRequest;
use App\Http\Requests\EditFacturaRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\TipoGasto;
use App\Proveedor;
use App\Moneda;
use App\Factura;
use App\Liquidacion;
use App\UsuarioRuta;
use App\Presupuesto;
use Illuminate\Support\Facades\Session;

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
         $subcategoria = SubcategoriaTipoGasto::where('ANULADO', '=', 0)->lists('DESCRIPCION', 'ID')->toArray();
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

         $moneda = Empresa::select('cat_moneda.ID', 'cat_moneda.DESCRIPCION')
             ->join('cat_moneda', 'cat_moneda.ID', '=', 'cat_empresa.MONEDA_ID')
             ->where('cat_empresa.ID', '=',  $empresa_id)
             ->first();

         $fechaFactura = null;

         $tipoDocumento = TipoDocumento::lists('DESCRIPCION', 'ID')->toArray();

        // $factura->CANTIDAD_PORCENTAJE_CUSTOM = null;


         return view('facturas.create', compact('liquidacion_id', 'tipoGasto', 'proveedor', 'moneda', 'fechaFactura', 'tipoProveedor',
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
        $factura = new Factura();

        if ($request->FMONEDA_ID == 2) {            
            $montoConversion = round(($request->TOTAL * $request->TASA_CAMBIO), 4);
        } else {
            $montoConversion = $request->TOTAL * 1;
        }
        
        //dd($montoConversion);
        /** Procesa Imagen **/

        $file = $request->file('FOTO');
        $name = $request->LIQUIDACION_ID . '-' . $request->NUMERO . '-' . time() . '-' . $file->getClientOriginalName();

        $path = public_path() . '/images/' .  Auth::user()->email ;

        if (file_exists($path)) {

        } else {
            mkdir($path, 0700);
        }
        $file->move($path,$name);

        

        //Se valida que fecha no sea anterior a X días programados por la empresa y dentro de Período de Liquidación

        $empresa = Session::get('loginEmpresa');
        
        if($request->TIPO_LIQUIDACION == 'Rutas') {            
            $restriccionDias = Empresa::select('TIEMPOATRASO_RUTAS')->where('ID', '=', $empresa)->first();
            $limite = Liquidacion::select('FECHA_INICIO', 'FECHA_FINAL')->where('ID', '=', $request->LIQUIDACION_ID)->first();
            //dd($limite->FECHA_INICIO->format('d-m-Y'));
            //dd($restriccionDias);
            //dd($restriccionDias);
            $fechaFactura = date_create($request->FECHA_FACTURA);
            if ($fechaFactura->format('Y-m-d') < $limite->FECHA_INICIO->subDays($restriccionDias->TIEMPOATRASO_RUTAS)->format('Y-m-d')) {
                return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango Permitido!');            
            }
            if ($fechaFactura->format('Y-m-d') > $limite->FECHA_FINAL->format('Y-m-d')) {
                return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango Permitido!');            
            }    
        } else {
            $restriccionDias = Empresa::select('TIEMPOATRASO_OTROSGASTOS')->where('ID', '=', $empresa)->first();
            $limite = Liquidacion::select('FECHA_INICIO', 'FECHA_FINAL')->where('ID', '=', $request->LIQUIDACION_ID)->first();
            $fechaFactura = date_create($request->FECHA_FACTURA);
            if ($fechaFactura->format('Y-m-d') < $limite->FECHA_INICIO->subDays($restriccionDias->TIEMPOATRASO_OTROSGASTOS)->format('Y-m-d')) {
                return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango Permitido!');            
            }
            if ($fechaFactura->format('Y-m-d') > $limite->FECHA_FINAL->format('Y-m-d')) {
                return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango Permitido!');            
            }                
        }
        
        

        //dd('Esta es la fecha de la factura ' . $fechaFactura->format('Y-m-d') . ' y esta la de limite ' . $limite->FECHA_INICIO->subDays($restriccionDias->TIEMPOATRASO_RUTAS)->format('Y-m-d'));
        
          

        


        
        //dd($limite->FECHA_INICIO->subDays($restriccionDias->TIEMPOATRASO_RUTAS)->format('d-m-Y'));
       

/*
        $detPresupuestoId = Liquidacion::select('liq_liquidacion.USUARIORUTA_ID')
                                      ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                      ->join('pre_presupuesto', 'pre_presupuesto.USUARIORUTA_ID', '=', 'cat_usuarioruta.ID')
                                      ->join('pre_detpresupuesto', 'pre_detpresupuesto.PRESUPUESTO_ID', '=', 'pre_presupuesto.ID')
                                      ->whereBetween($request->FECHA_FACTURA, ['pre_presupuesto.VIGENCIA_INICIO','pre_presupuesto.VIGENCIA_FINAL'])
                                      ->where('pre_detpresupuesto.TIPOGASTO_ID','=', $request->TIPOGASTO_ID)
                                      ->where('liq_liquidacion.ID', '=', $request->LIQUIDACION_ID)
                                      ->get();
                                      dd($detPresupuestoId);

       select dp.PRESUPUESTO_ID
	from cat_usuarioruta as ur
		inner join pre_presupuesto as p
			on p.USUARIORUTA_ID = ur.ID
				and  @fechaFactura between '2017-06-22' and '2017-06-30'
		inner join pre_detpresupuesto dp
			on dp.PRESUPUESTO_ID = p.ID
				and dp.TIPOGASTO_ID = @tipogastoId
	where ur.ID = @usuarioRutaId;*/

//dd('PRIMER DATO:  ' . $request->PRESUPUESTO_ID . ' SEGUNDO DATO: ' . $request->TIPOGASTO_ID);

        /** Se obtiene No. de Detalle Presupuesto al que Corresponde **/
//dd($request->all());
        if(($request->TIPO_LIQUIDACION == 'Otros Gastos') && ($request->CATEGORIA_GASTO == 'combustible' )) {
            $detallePresupuesto = Presupuesto::select('ID', 'ASIGNACION_MENSUAL as MONTO')->where('ID', '=', $request->PRESUPUESTO_ID)->first();
            //dd('ahi vamos...' . $detallePresupuesto->MONTO);
        } else {
            $detallePresupuesto = DetallePresupuesto::select('ID', 'MONTO')
                                                        ->where('PRESUPUESTO_ID', '=', $request->PRESUPUESTO_ID)
                                                        ->where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                                                        ->first();
        }

        /**  Se valida si es combustible que se ingrese la cantidad correspondiente de galones  **/
        if ($request->CATEGORIA_GASTO == 'combustible') {
            if($request->CANTIDAD_PORCENTAJE_CUSTOM === '') {
                return back()->withInput()->with('info', 'Es obligatorio que ingrese la cantidad de galones facturados!');
            } 
        }              
       

        /** Se obtiene monto gastado hasta el momento por tipo de gasto **/
        if ($request->CATEGORIA_GASTO == 'combustible') {
            $montoAcumulado = Factura::where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)
            ->where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
            ->sum('CANTIDAD_PORCENTAJE_CUSTOM');
            
        } else {
            $montoAcumulado = Factura::where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)
            ->where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
            ->sum('TOTAL');
        }
        

        $saldo = $detallePresupuesto->MONTO - $montoAcumulado;

        
        //Se determina si tiene presupuesto para cubrir el gasto o si existe remanente

        if ($request->CATEGORIA_GASTO == 'combustible') {
            
            $idp = SubcategoriaTipoGasto::select('MONTO_A_APLICAR')->where('ID', '=', $request->SUBCATEGORIATIPOGASTO_ID)->first();
            
            if ($saldo > 0) {
                $saldoFactura = $saldo - $request->CANTIDAD_PORCENTAJE_CUSTOM;
                
                if ($saldoFactura > 0) {
                    $factura->APROBACION_PAGO = 1;
    
                    /** Operaciones de Calculo **/
                    $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 2);
                    
                    $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + 0.12)),2); //Se calcula monto afecto
                    
                    $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto
                    
                } else {
                    
                    $saldoParcial = $saldo;
                    
                    $remanente = $request->CANTIDAD_PORCENTAJE_CUSTOM - $saldoParcial; //Ojo aca puede ser util                

                    $precioGalon = round(($montoConversion / $request->CANTIDAD_PORCENTAJE_CUSTOM), 2);

                    $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 2);
                    
                    $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + 0.12)),2); //Se calcula monto afecto
                    
                    $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto

                    //$factura->MONTO_EXENTO = $reembolsable * $precioGalon *  
                    $factura->MONTO_REMANENTE = round(($remanente * $precioGalon), 2);    
                    $factura->APROBACION_PAGO = 1;
                    
                }
    
            } else {            
                $factura->APROBACION_PAGO = 0;
                
                /** Operaciónes de Calculo **/
                $factura->MONTO_REMANENTE = $montoConversion;            
            }
            
        } else if ($request->CATEGORIA_GASTO == 'depreciación') {
            
            $idp = SubcategoriaTipoGasto::select('MONTO_A_APLICAR')->where('ID', '=', $request->SUBCATEGORIATIPOGASTO_ID)->first();
            
            if ($saldo > 0) {
                $saldoFactura = $saldo - $montoConversion;
                
                if ($saldoFactura > 0) {
                    $factura->APROBACION_PAGO = 1;
    
                    /** Operaciones de Calculo **/
                    $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 2);
                    
                    $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + 0.12)),2); //Se calcula monto afecto
                    
                    $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto
                   
                } else {
                    
                    $saldoParcial = $saldo;
                    
                    $remanente = $montoConversion - $saldoParcial; //Ojo aca puede ser util                

                    //$precioGalon = round(($montoConversion / $request->CANTIDAD_PORCENTAJE_CUSTOM), 2);

                    $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 2);
                    
                    $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + 0.12)),2); //Se calcula monto afecto
                    
                    $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto

                    //$factura->MONTO_EXENTO = $reembolsable * $precioGalon *  
                    //$factura->MONTO_REMANENTE = round(($remanente * $precioGalon), 2);    
                    $factura->MONTO_REMANENTE = round($remanente, 2);
                    $factura->APROBACION_PAGO = 1;
                    
                }
    
            } else {            
                $factura->APROBACION_PAGO = 0;
                
                /** Operaciónes de Calculo **/
                $factura->MONTO_REMANENTE = $montoConversion;            
            }
        } else {
           
            $findMe = 'con';
            $impuestoHotel = strpos(strtolower($request->SUBCATEGORIA_GASTO), $findMe); 
            if($impuestoHotel !== false)            
            {   
                $impuesto = SubcategoriaTipoGasto::select('MONTO_A_APLICAR')->where('ID', '=', $request->SUBCATEGORIATIPOGASTO_ID)->first();
                $factura->CANTIDAD_PORCENTAJE_CUSTOM = $impuesto->MONTO_A_APLICAR;
                if ($saldo > 0) {
                    $saldoFactura = $saldo - $montoConversion;
                    
                    if ($saldoFactura > 0) {
                        $factura->APROBACION_PAGO = 1;
        
                        /** Operaciones de Calculo **/                                               
                        $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + 0.12 + $impuesto->MONTO_A_APLICAR)),2); //Se calcula monto afecto

                        $factura->MONTO_EXENTO = round(($factura->MONTO_AFECTO * $impuesto->MONTO_A_APLICAR), 2);
                        
                        $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto
                        
                    } else {
                        
                        $saldoParcial = $saldo;
                        
                        $remanente = $montoConversion - $saldoParcial; //Ojo aca puede ser util
    
                        $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + 0.12 + $impuesto->MONTO_A_APLICAR)),2); //Se calcula monto afecto
                        
                        $factura->MONTO_EXENTO = round(($factura->MONTO_AFECTO * $impuesto->MONTO_A_APLICAR), 2);
                        
                        $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto

                        $factura->MONTO_REMANENTE = round($remanente, 2);
                            
                        $factura->APROBACION_PAGO = 1;
                        
                    }
        
                } else {            
                    $factura->APROBACION_PAGO = 0;
                    
                    /** Operaciónes de Calculo **/
                    $factura->MONTO_REMANENTE = $montoConversion;            
                }                

                
            } else {
                if ($saldo > 0) {
                    $saldoFactura = $saldo - $montoConversion;
                    if ($saldoFactura > 0) {
                        $factura->APROBACION_PAGO = 1;
        
                        /** Operaciones de Calculo **/
        
                        $factura->MONTO_AFECTO = round(($montoConversion / (1 + 0.12)),2); //Se calcula monto afecto
                
                        $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto
        
                    } else {                        
                        $saldoParcial = $saldo;

                        $factura->MONTO_AFECTO = round(($montoConversion / (1 + 0.12)),2); //Se calcula monto afecto
                        
                        $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto
                        $factura->MONTO_EXENTO = 0;
                        
                        $factura->MONTO_REMANENTE = $montoConversion - $saldoParcial;                
                        
                        $factura->APROBACION_PAGO = 1;
                    }
        
                } else {            
                    $factura->APROBACION_PAGO = 0;
                
                    /** Operaciónes de Calculo **/
                    $factura->MONTO_REMANENTE = $montoConversion;            
                }

            }
            
        }
        

        if ($request->CANTIDAD_PORCENTAJE_CUSTOM == '') {
            $request->CANTIDAD_PORCENTAJE_CUSTOM = 0.00;
        } 

        //dd('Este es el Pago Parcial: ' . $saldoParcial . ' y este el Remanente: ' . $remanente);

        //dd('vamos mal o punto de control');

        $factura->LIQUIDACION_ID = $request->LIQUIDACION_ID;
        $factura->TIPOGASTO_ID = $request->TIPOGASTO_ID;
        $factura->DETPRESUPUESTO_ID = $detallePresupuesto->ID;
        $factura->MONEDA_ID = $request->MONEDA_ID;
        $factura->PROVEEDOR_ID = $request->PROVEEDOR_ID;
        $factura->CAUSAEXENCION_ID = 1;
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


        $subcategoria = SubcategoriaTipoGasto::where('ANULADO', '=', 0)->lists('DESCRIPCION', 'ID')->toArray();
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

        $moneda = Empresa::select('cat_moneda.ID', 'cat_moneda.DESCRIPCION')
            ->join('cat_moneda', 'cat_moneda.ID', '=', 'cat_empresa.MONEDA_ID')
            ->where('cat_empresa.ID', '=',  $empresa_id)
            ->first();

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
            

        return view('facturas.edit', compact('factura', 'tipoGasto', 'proveedor', 'moneda', 'fechaFactura', 'tipoProveedor', 'liquidacion_id', 'tipoDocumento',
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

        $factura = Factura::findOrFail($id);
        //Se valida que fecha no sea anterior a X días programados por la empresa y dentro de Período de Liquidación

        $empresa = Session::get('loginEmpresa');
        
                if($request->TIPO_LIQUIDACION == 'Rutas') {            
                    $restriccionDias = Empresa::select('TIEMPOATRASO_RUTAS')->where('ID', '=', $empresa)->first();
                    $limite = Liquidacion::select('FECHA_INICIO', 'FECHA_FINAL')->where('ID', '=', $request->LIQUIDACION_ID)->first();
                    //dd($limite->FECHA_INICIO->format('d-m-Y'));
                    //dd($restriccionDias);
                    $fechaFactura = date_create($request->FECHA_FACTURA);
                    if ($fechaFactura->format('Y-m-d') < $limite->FECHA_INICIO->subDays($restriccionDias->TIEMPOATRASO_RUTAS)->format('Y-m-d')) {
                        return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango Permitido!');            
                    }
                    if ($fechaFactura->format('Y-m-d') > $limite->FECHA_FINAL->format('Y-m-d')) {
                        return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango Permitido!');            
                    }    
                } else {
                    $restriccionDias = Empresa::select('TIEMPOATRASO_OTROSGASTOS')->where('ID', '=', $empresa)->first();
                    $limite = Liquidacion::select('FECHA_INICIO', 'FECHA_FINAL')->where('ID', '=', $request->LIQUIDACION_ID)->first();
                    $fechaFactura = date_create($request->FECHA_FACTURA);
                    if ($fechaFactura->format('Y-m-d') < $limite->FECHA_INICIO->subDays($restriccionDias->TIEMPOATRASO_OTROSGASTOS)->format('Y-m-d')) {
                        return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango Permitido!');            
                    }
                    if ($fechaFactura->format('Y-m-d') > $limite->FECHA_FINAL->format('Y-m-d')) {
                        return back()->withInput()->with('info', 'La Fecha de esta Factura se encuentra fuera del Rango Permitido!');            
                    }                
                }
               
        
                /** Se obtiene No. de Detalle Presupuesto al que Corresponde **/
        
                if($request->CATEGORIA_GASTO =='depreciación' ) {
                    $detallePresupuesto = Presupuesto::select('ID', 'ASIGNACION_MENSUAL as MONTO')->where('ID', '=', $request->PRESUPUESTO_ID)->first();
                    //dd('ahi vamos...' . $detallePresupuesto->MONTO);
                } else {
                    $detallePresupuesto = DetallePresupuesto::select('ID', 'MONTO')
                                                                ->where('PRESUPUESTO_ID', '=', $request->PRESUPUESTO_ID)
                                                                ->where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                                                                ->first();
                }
        
                /**  Se valida si es combustible que se ingrese la cantidad correspondiente de galones  **/
                if ($request->CATEGORIA_GASTO == 'combustible') {
                    if($request->CANTIDAD_PORCENTAJE_CUSTOM === '') {
                        return back()->withInput()->with('info', 'Es obligatorio que ingrese la cantidad de galones facturados!');
                    } 
                }              
               
        
                /** Se obtiene monto gastado hasta el momento por tipo de gasto **/
                if ($request->CATEGORIA_GASTO == 'combustible') {
                    $montoAcumulado = Factura::where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)
                    ->where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                    ->sum('CANTIDAD_PORCENTAJE_CUSTOM');
                    
                } else {
                    $montoAcumulado = Factura::where('LIQUIDACION_ID', '=', $request->LIQUIDACION_ID)
                    ->where('TIPOGASTO_ID', '=', $request->TIPOGASTO_ID)
                    ->sum('TOTAL');
                }
                
                //dd($factura->APROBACION_PAGO);
                //dd('Presupuesto: ' . $detallePresupuesto->MONTO . ' Y el monto acumulado es :' . $montoAcumulado . ' y factura: ' . $factura->TOTAL);
                $montoAcumulado -= $factura->TOTAL;
               
                $saldo = $detallePresupuesto->MONTO - $montoAcumulado;        
                //dd($saldo);

                //Se determina si tiene presupuesto para cubrir el gasto o si existe remanente
        
                if ($request->CATEGORIA_GASTO == 'combustible') {
                    
                    $idp = SubcategoriaTipoGasto::select('MONTO_A_APLICAR')->where('ID', '=', $request->SUBCATEGORIATIPOGASTO_ID)->first();
                    
                    if ($saldo > 0) {
                        $saldoFactura = $saldo - $request->CANTIDAD_PORCENTAJE_CUSTOM;
                        
                        if ($saldoFactura > 0) {
                            $factura->APROBACION_PAGO = 1;
            
                            /** Operaciones de Calculo **/
                            $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 2);
                            
                            $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + 0.12)),2); //Se calcula monto afecto
                            
                            $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto

                            $factura->MONTO_REMANENTE = 0;
                            
                        } else {
                            
                            $saldoParcial = $saldo;
                            
                            $remanente = $request->CANTIDAD_PORCENTAJE_CUSTOM - $saldoParcial; //Ojo aca puede ser util                
        
                            $precioGalon = round(($montoConversion / $request->CANTIDAD_PORCENTAJE_CUSTOM), 2);
        
                            $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 2);
                            
                            $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + 0.12)),2); //Se calcula monto afecto
                            
                            $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto
        
                            //$factura->MONTO_EXENTO = $reembolsable * $precioGalon *  
                            $factura->MONTO_REMANENTE = round(($remanente * $precioGalon), 2);    
                            $factura->APROBACION_PAGO = 1;
                            
                        }
            
                    } else {            
                        $factura->APROBACION_PAGO = 0;
                        
                        /** Operaciónes de Calculo **/
                        $factura->MONTO_REMANENTE = $montoConversion;            
                    }
                    
                } else if ($request->CATEGORIA_GASTO == 'depreciación') {
                    
                    $idp = SubcategoriaTipoGasto::select('MONTO_A_APLICAR')->where('ID', '=', $request->SUBCATEGORIATIPOGASTO_ID)->first();
                    
                    if ($saldo > 0) {
                        $saldoFactura = $saldo - $montoConversion;
                        
                        if ($saldoFactura > 0) {
                            $factura->APROBACION_PAGO = 1;
            
                            /** Operaciones de Calculo **/
                            $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 2);
                            
                            $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + 0.12)),2); //Se calcula monto afecto
                            
                            $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto

                            $factura->MONTO_REMANENTE = 0;
                           
                        } else {
                            
                            $saldoParcial = $saldo;
                            
                            $remanente = $montoConversion - $saldoParcial; //Ojo aca puede ser util                
        
                            //$precioGalon = round(($montoConversion / $request->CANTIDAD_PORCENTAJE_CUSTOM), 2);
        
                            $factura->MONTO_EXENTO = round(($request->CANTIDAD_PORCENTAJE_CUSTOM * $idp->MONTO_A_APLICAR), 2);
                            
                            $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + 0.12)),2); //Se calcula monto afecto
                            
                            $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto
        
                            //$factura->MONTO_EXENTO = $reembolsable * $precioGalon *  
                            //$factura->MONTO_REMANENTE = round(($remanente * $precioGalon), 2);    
                            $factura->MONTO_REMANENTE = round($remanente, 2);
                            $factura->APROBACION_PAGO = 1;
                            
                        }
            
                    } else {            
                        $factura->APROBACION_PAGO = 0;
                        
                        /** Operaciónes de Calculo **/
                        $factura->MONTO_REMANENTE = $montoConversion;            
                    }
                } else {                
                    
                    $findMe = 'con';
                    $impuestoHotel = strpos(strtolower($request->SUBCATEGORIA_GASTO), $findMe); 
                    if($impuestoHotel !== false)            
                    {   
                        $impuesto = SubcategoriaTipoGasto::select('MONTO_A_APLICAR')->where('ID', '=', $request->SUBCATEGORIATIPOGASTO_ID)->first();
                        $factura->CANTIDAD_PORCENTAJE_CUSTOM = $impuesto->MONTO_A_APLICAR;
                        if ($saldo > 0) {
                            $saldoFactura = $saldo - $montoConversion;
                            
                            if ($saldoFactura > 0) {
                                $factura->APROBACION_PAGO = 1;
                
                                /** Operaciones de Calculo **/                                               
                                $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + 0.12 + $impuesto->MONTO_A_APLICAR)),2); //Se calcula monto afecto
        
                                $factura->MONTO_EXENTO = round(($factura->MONTO_AFECTO * $impuesto->MONTO_A_APLICAR), 2);
                                
                                $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto

                                $factura->MONTO_REMANENTE = 0;
                                
                            } else {
                               // dd('detenerse aqui!!!')   ;        
                                $saldoParcial = $saldo;
                                
                                $remanente = $montoConversion - $saldoParcial; //Ojo aca puede ser util
            
                                $factura->MONTO_AFECTO = round((($montoConversion - $factura->MONTO_EXENTO) / (1 + 0.12 + $impuesto->MONTO_A_APLICAR)),2); //Se calcula monto afecto
                                
                                $factura->MONTO_EXENTO = round(($factura->MONTO_AFECTO * $impuesto->MONTO_A_APLICAR), 2);
                                
                                $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto
        
                                $factura->MONTO_REMANENTE = round($remanente, 2);
                                    
                                $factura->APROBACION_PAGO = 1;
                                
                            }
                
                        } else {            
                            $factura->APROBACION_PAGO = 0;
                            
                            /** Operaciónes de Calculo **/
                            $factura->MONTO_REMANENTE = $montoConversion;            
                        }                
        
                        
                    } else {                       
                        if ($saldo > 0) {
                            $saldoFactura = $saldo - $montoConversion;
                            if ($saldoFactura > 0) {
                                $factura->APROBACION_PAGO = 1;
                
                                /** Operaciones de Calculo **/
                
                                $factura->MONTO_AFECTO = round(($montoConversion / (1 + 0.12)),2); //Se calcula monto afecto
                        
                                $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto

                                $factura->MONTO_REMANENTE = 0;
                
                            } else {                        
                                $saldoParcial = $saldo;
        
                                $factura->MONTO_AFECTO = round(($montoConversion / (1 + 0.12)),2); //Se calcula monto afecto
                                
                                $factura->MONTO_IVA = round(($factura->MONTO_AFECTO * 0.12 ), 2); //Se calcula monto de impuesto
                                $factura->MONTO_EXENTO = 0;
                                
                                $factura->MONTO_REMANENTE = $montoConversion - $saldoParcial;                
                                
                                $factura->APROBACION_PAGO = 1;
                            }
                
                        } else {            
                            $factura->APROBACION_PAGO = 0;
                        
                            /** Operaciónes de Calculo **/
                            $factura->MONTO_REMANENTE = $montoConversion;            
                        }
        
                    }
                    
                }
                
        
                if ($request->CANTIDAD_PORCENTAJE_CUSTOM == '') {
                    $request->CANTIDAD_PORCENTAJE_CUSTOM = 0.00;
                } 
        
        Factura::where('ID', $id)
                ->update(['TIPOGASTO_ID' => $request->TIPOGASTO_ID, 'MONEDA_ID' => $request->MONEDA_ID, 'PROVEEDOR_ID' => $request->PROVEEDOR_ID, 
                          'KILOMETRAJE_INICIAL' => $request->KM_INICIO, 'KILOMETRAJE_FINAL' => $request->KM_FINAL, 'CORRECCION' => 0,
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
        $anulado = Factura::where('id', '=', $id)->pluck('anulado');
       
            if ($anulado == 1) {
                Factura::where('id', $id)
                            ->update(['ANULADO' => 0]);
                $anular = 'No';
            } else {
                Factura::where('id', $id)
                ->update(['ANULADO' => 1]);            
                $anular = 'Si';
            }  
            return $anular;  
        
    }
}

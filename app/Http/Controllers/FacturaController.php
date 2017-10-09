<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\TipoDocumento;
use App\TipoProveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateFacturaRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\TipoGasto;
use App\Proveedor;
use App\Moneda;
use App\Factura;
use App\Liquidacion;
use Illuminate\Support\Facades\Session;

class FacturaController extends Controller
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
         $liquidacion_id = $id;
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
         $fechas =  Liquidacion::select('liq_liquidacion.FECHA_INICIO', 'liq_liquidacion.FECHA_FINAL')
                                        ->where('liq_liquidacion.ID', '=', $liquidacion_id)
                                        ->first();


         $fechaInicio = $fechas->FECHA_INICIO;
         $fechaFinal = $fechas->FECHA_FINAL;

         $tipoGasto = Liquidacion::join('pre_presupuesto', 'pre_presupuesto.ID', '=', 'liq_liquidacion.PRESUPUESTO_ID')
                                     ->join('pre_detpresupuesto', 'pre_detpresupuesto.PRESUPUESTO_ID', '=', 'pre_presupuesto.ID' )
                                     ->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'pre_detpresupuesto.TIPOGASTO_ID')
                                     ->where('liq_liquidacion.ID', '=', $liquidacion_id)
                                     ->lists('cat_tipogasto.DESCRIPCION', 'cat_tipogasto.ID')
                                     ->toArray();

         /** Esta es la consulta original!!
         $tipoGasto =  Liquidacion::join('pre_presupuesto', 'pre_presupuesto.USUARIORUTA_ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                  ->join('pre_detpresupuesto', 'pre_detpresupuesto.PRESUPUESTO_ID', '=', 'pre_presupuesto.ID' )
                                  ->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'pre_detpresupuesto.TIPOGASTO_ID')
                                  ->whereDate('pre_presupuesto.VIGENCIA_INICIO', '=', $fechaInicio)
                                  ->whereDate('pre_presupuesto.VIGENCIA_FINAL', '=', $fechaFinal)
                                  ->where('liq_liquidacion.ID', '=', $liquidacion_id)
                                  ->lists('cat_tipogasto.DESCRIPCION', 'cat_tipogasto.ID')
                                  ->toArray();
         **/
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


         return view('facturas.create', compact('liquidacion_id', 'tipoGasto', 'proveedor', 'moneda', 'fechaFactura', 'tipoProveedor', 'tipoDocumento'));
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

        $file = $request->file('FOTO');
        $name = $request->LIQUIDACION_ID . '-' . $request->NUMERO . '-' . time() . '-' . $file->getClientOriginalName();

        $path = public_path() . '/images/' .  Auth::user()->email ;

        if (file_exists($path)) {

        } else {
            mkdir($path, 0700);
        }
        $file->move($path,$name);

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

        $factura->LIQUIDACION_ID = $request->LIQUIDACION_ID;
        $factura->TIPOGASTO_ID = $request->TIPOGASTO_ID;
        $factura->DETPRESUPUESTO_ID = 1;
        $factura->MONEDA_ID = $request->MONEDA_ID;
        $factura->PROVEEDOR_ID = $request->PROVEEDOR_ID;
        $factura->CAUSAEXENCION_ID = 1;
        $factura->SERIE = $request->SERIE;
        $factura->NUMERO = $request->NUMERO;
        $factura->FECHA_FACTURA = $request->FECHA_FACTURA;
        $factura->CANTIDAD_PORCENTAJE_CUSTOM = $request->CANTIDAD_PORCENTAJE;
        $factura->TIPODOCUMENTO_ID = $request->TIPODOCUMENTO_ID;
        $factura->KILOMETRAJE_INICIAL = $request->KM_INICIO;
        $factura->KILOMETRAJE_FINAL = $request->KM_FINAL;
        $factura->COMENTARIO_PAGO = $request->COMENTARIO_PAGO;
        $factura->TOTAL = $request->TOTAL;
        $factura->FOTO = $name;
        $factura->ANULADO = 0;

        $factura->save();

        return Redirect::to('liquidaciones/' . $request->LIQUIDACION_ID . '/edit');
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
        $factura = Factura::findOrFail($id);

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

        return view('facturas.edit', compact('factura', 'tipoGasto', 'proveedor', 'moneda', 'fechaFactura', 'tipoProveedor', 'liquidacion_id', 'tipoDocumento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateFacturaRequest $request, $id)
    {
        Factura::where('ID', $id)
                ->update(['TIPOGASTO_ID' => $request->TIPOGASTO_ID, 'MONEDA_ID' => $request->MONEDA_ID, 'PROVEEDOR_ID' => $request->PROVEEDOR_ID, 'COMENTARIO_PAGO' => $request->COMENTARIO_PAGO,
                          'TIPODOCUMENTO_ID' => $request->TIPODOCUMENTO, 'KILOMETRAJE_INICIAL' => $request->KM_INICIO, 'KILOMETRAJE_FINAL' => $request->KM_FINAL,
                          'SERIE' => $request->SERIE, 'NUMERO' => $request->NUMERO, 'FECHA_FACTURA' => $request->FECHA_FACTURA, 'TOTAL' => $request->TOTAL]);



        return Redirect::to('liquidaciones/' . $request->LIQUIDACION_ID . '/edit');
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
                ->update(['COMENTARIO_SUPERVISOR' => $nuevoComentario]);

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
                ->update(['COMENTARIO_CONTABILIDAD' => $nuevoComentario]);

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
        Factura::where('ID', $id)
            ->update(['ANULADO' => 1]);
        return 1; //Redirect::to('liquidaciones');
    }
}

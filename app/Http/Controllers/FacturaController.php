<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\TipoGasto;
use App\Proveedor;
use App\Moneda;
use App\Factura;
use App\Liquidacion;

class FacturaController extends Controller
{
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

         $tipoGasto = TipoGasto::lists('DESCRIPCION', 'ID')
                                         ->toArray();

         $proveedor = Proveedor::lists('IDENTIFICADOR_TRIBUTARIO', 'ID')
                                         ->toArray();

         $moneda = Moneda::lists('DESCRIPCION', 'ID')
                                         ->toArray();

         return view('facturas.create', compact('liquidacion_id', 'tipoGasto', 'proveedor', 'moneda'));
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $factura = new Factura();

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
        $factura->TOTAL = $request->TOTAL;
        $factura->ANULADO = 0;

        $factura->save();

        return Redirect::to('liquidaciones');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

        $tipoGasto = TipoGasto::lists('DESCRIPCION', 'ID')
                                        ->toArray();

        $proveedor = Proveedor::lists('IDENTIFICADOR_TRIBUTARIO', 'ID')
                                        ->toArray();

        $moneda = Moneda::lists('DESCRIPCION', 'ID')
                                        ->toArray();


        return view('facturas.edit', compact('factura', 'tipoGasto', 'proveedor', 'moneda'));
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
        Factura::where('ID', $id)
                ->update(['TIPOGASTO_ID' => $request->TIPOGASTO_ID, 'MONEDA_ID' => $request->MONEDA_ID, 'PROVEEDOR_ID' => $request->PROVEEDOR_ID,
                          'SERIE' => $request->SERIE, 'NUMERO' => $request->NUMERO, 'FECHA_FACTURA' => $request->FECHA_FACTURA, 'TOTAL' => $request->TOTAL]);



        return Redirect::to('liquidaciones');
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
}

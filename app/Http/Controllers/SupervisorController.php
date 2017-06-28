<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Factura;
use App\Liquidacion;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $liquidaciones = Liquidacion::select('liq_liquidacion.ID', 'liq_liquidacion.FECHA_INICIO as FECHA', 'users.nombre as USUARIO', 'cat_ruta.DESCRIPCION as RUTA' )
                                  ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                  ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                  ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                  ->paginate(10);

                                /*  $total = Factura::where('LIQUIDACION_ID', '=', 4)->sum('TOTAL');
                                  dd($total);
                                  $totalLiquidacion = array();
                                  foreach ($liquidaciones as $liquidacion) {
                                       echo $liquidacion->ID;
                                       array_push($totalLiquidacion, "arándano");

                                  }


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
        $liquidacion = Liquidacion::select('liq_liquidacion.ID', 'liq_liquidacion.FECHA_INICIO as FECHA', 'users.nombre as USUARIO', 'cat_ruta.DESCRIPCION as RUTA' )
                                  ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'liq_liquidacion.USUARIORUTA_ID')
                                  ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                  ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                  ->where('liq_liquidacion.id', '=', $id)
                                  ->first();

        $facturas = Factura::select('liq_factura.ID', 'cat_proveedor.NOMBRE', 'liq_factura.SERIE as SERIE', 'liq_factura.NUMERO as NUMERO', 'liq_factura.TOTAL as TOTAL', 'liq_factura.FECHA_FACTURA AS FECHA', 'cat_tipogasto.DESCRIPCION as TIPOGASTO')
                                                  ->join('cat_proveedor', 'cat_proveedor.ID', '=', 'liq_factura.PROVEEDOR_ID')
                                                  ->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'liq_factura.TIPOGASTO_ID')
                                                  //->join('cat_frecuenciatiempo', 'cat_frecuenciatiempo.ID', '=', 'pre_detpresupuesto.FRECUENCIATIEMPO_ID')
                                                  ->where('liq_factura.LIQUIDACION_ID', '=', $id)
                                                  ->paginate();

        return view('supervisor.edit', compact('liquidacion', 'facturas'));
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

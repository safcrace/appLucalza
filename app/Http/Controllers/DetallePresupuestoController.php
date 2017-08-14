<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDetallePresupuestoRequest;
use App\TipoAsignacion;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\FrecuenciaTiempo;
use App\TipoGasto;
use App\DetallePresupuesto;
use Illuminate\Support\Facades\Session;

class DetallePresupuestoController extends Controller
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
     public function presupuestoCreateDetalle($id)
     {
         $presupuesto_id = $id;

         $empresa_id = Session::get('empresa');

         $frecuencia = FrecuenciaTiempo::lists('DESCRIPCION', 'ID')
                                         ->toArray();

         $tipoGasto = TipoGasto::where('EMPRESA_ID', '=', $empresa_id)
                                        ->lists('DESCRIPCION', 'ID')
                                        ->toArray();

         $tipoAsignacion = TipoAsignacion::lists('DESCRIPCION', 'ID')
             ->toArray();

         return view('detallePresupuestos.create', compact('presupuesto_id', 'tipoGasto', 'frecuencia', 'tipoAsignacion'));
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDetallePresupuestoRequest $request)
    {
        $detallePresupuesto = new DetallePresupuesto();
        $detallePresupuesto->PRESUPUESTO_ID = $request->PRESUPUESTO_ID;
        $detallePresupuesto->TIPOGASTO_ID = $request->TIPOGASTO_ID;
        $detallePresupuesto->TIPOASIGNACION_ID = $request->TIPOASIGNACION_ID;
        $detallePresupuesto->MONTO = $request->MONTO;
        $detallePresupuesto->FRECUENCIATIEMPO_ID = $request->FRECUENCIATIEMPO_ID;
        $detallePresupuesto->CENTROCOSTO1 = $request->CENTROCOSTO1;
        $detallePresupuesto->CENTROCOSTO2 = $request->CENTROCOSTO2;
        $detallePresupuesto->CENTROCOSTO3 = $request->CENTROCOSTO3;
        $detallePresupuesto->CENTROCOSTO4 = $request->CENTROCOSTO4;
        $detallePresupuesto->CENTROCOSTO5 = $request->CENTROCOSTO5;
        $detallePresupuesto->ANULADO = $request->ANULADODP;
        if ($detallePresupuesto->ANULADO === null) {
            $detallePresupuesto->ANULADO = 0;
        }

        $detallePresupuesto->save();

        return Redirect::to('presupuestos/' . $request->PRESUPUESTO_ID . '/edit' );

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
        $detallePresupuesto = DetallePresupuesto::findOrFail($id);

        $frecuencia = FrecuenciaTiempo::lists('DESCRIPCION', 'ID')
                                        ->toArray();

        $tipoGasto = TipoGasto::lists('DESCRIPCION', 'ID')
                                        ->toArray();

        $tipoAsignacion = TipoAsignacion::lists('DESCRIPCION', 'ID')
            ->toArray();

        return view('detallePresupuestos.edit', compact('detallePresupuesto', 'frecuencia', 'tipoGasto', 'tipoAsignacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateDetallePresupuestoRequest $request, $id)
    {
        if ($request->ANULADO === null) {
            $request->ANULADO = 0;
        }
        DetallePresupuesto::where('ID', $id)
                ->update(['PRESUPUESTO_ID' => $request->PRESUPUESTO_ID, 'TIPOGASTO_ID' => $request->TIPOGASTO_ID, 'FRECUENCIATIEMPO_ID' => $request->FRECUENCIATIEMPO_ID,
                          'MONTO' => $request->MONTO, 'CENTROCOSTO1' => $request->CENTROCOSTO1, 'CENTROCOSTO2' => $request->CESTROCOSTO2, 'CENTROCOSTO3' => $request->CENTROCOSTO3,
                          'CENTROCOSTO4' => $request->CENTROCOSTO4, 'CENTROCOSTO5' => $request->CESTROCOSTO5, 'TIPOASIGNACION_ID' => $request->TIPOASIGNACION_ID, 'ANULADO' => $request->ANULADO]);

        return Redirect::to('presupuestos/' . $request->PRESUPUESTO_ID . '/edit' );
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
        $param = explode('-', $id);
        $id = $param[0];
        $presupuesto_id = $param[1];
        DetallePresupuesto::where('ID', $id)
            ->update(['ANULADO' => 1]);

        return 1; //Redirect::to('presupuestos/' . $presupuesto_id . '/edit');
    }

}

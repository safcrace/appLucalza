<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Moneda;
use App\TasaCambio;

class MonedaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $monedas = Moneda::select('*')
                            ->where('cat_moneda.ANULADO', '=', 0)
                            ->paginate(3);

        return view('monedas.index', compact('monedas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('monedas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $moneda = new Moneda();
        $moneda->CLAVE = $request->CLAVE;
        $moneda->DESCRIPCION = $request->DESCRIPCION;
        $moneda->ANULADO = $request->ANULADO;
        if ($moneda->ANULADO === null) {
            $moneda->ANULADO = 0;
        }

        $moneda->save();

        $tasaCambio = new TasaCambio();

        $tasaCambio->FECHA = $request->FECHA;
        $tasaCambio->COMPRA = $request->COMPRA;
        $tasaCambio->VENTA = $request->VENTA;
        $tasaCambio->PROMEDIO = $request->PROMEDIO;
        $tasaCambio->ANULADO = $request->ANULADOTC;
        if ($tasaCambio->ANULADO === null) {
            $tasaCambio->ANULADO = 0;
        }

        $moneda->tasaCambios()->save($tasaCambio);

        return redirect::to('monedas');
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
        $moneda = Moneda::findOrFail($id);

        $tasaCambio = TasaCambio::select('cat_tasacambio.ID', 'cat_tasacambio.MONEDA_ID', 'cat_tasacambio.FECHA', 'cat_tasacambio.COMPRA', 'cat_tasacambio.VENTA', 'cat_tasacambio.PROMEDIO', 'cat_tasacambio.ANULADO')
                                  ->where('cat_tasacambio.MONEDA_ID', '=', $id)->paginate(4);

        //dd($tasaCambio);
        return view('monedas.edit', compact('moneda','tasaCambio'));
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
        $moneda = Moneda::findOrFail($id);
        //$moneda->fill($request->all());
        //$moneda->ID = $request->ID;

        $moneda->CLAVE = $request->CLAVE;
        $moneda->DESCRIPCION = $request->DESCRIPCION;
        $moneda->ANULADO = $request->ANULADO;
        //dd($moneda);
        $moneda->save();

        Moneda::where('ID', $moneda->ID)
          ->update(['CLAVE' => $request->CLAVE, 'DESCRIPCION' => $request->DESCRIPCION, 'ANULADO' => $request->ANULADO]);
        //dd('se supone que ya grabo');
        return Redirect::to('monedas');
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
        Moneda::where('ID', $id)
                ->update(['ANULADO' => 1]);

        return Redirect::to('monedas');
    }
}

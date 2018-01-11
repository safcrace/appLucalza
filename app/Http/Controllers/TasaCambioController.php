<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTasaCambioRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\TasaCambio;
use App\Moneda;
use Illuminate\Support\Facades\Session;

class TasaCambioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('roles:superAdmin,master');
    }

    public function createTasaCambio($id)
    {
        $moneda_id = $id;
        return view('monedas.tasaCambio.create', compact('moneda_id'));
    }

    public function store(CreateTasaCambioRequest $request)
    {
        $moneda = Moneda::find($request->MONEDA_ID);

        $existe = TasaCambio::where('MONEDA_ID', '=', $request->MONEDA_ID)->where('FECHA', '=', $request->FECHA)->first();

        if($existe) {
            Session::flash('fechaDuplicada', '¡No se puede ingresar dos tipos de cambio con la misma fecha!');
            return back()->withInput();
        } else{
            $tasaCambio = new TasaCambio();

            $tasaCambio->moneda_id = $moneda->ID;

            $tasaCambio->FECHA = $request->FECHA;
            $tasaCambio->COMPRA = $request->COMPRA;

            $tasaCambio->ANULADO = $request->ANULADOTC;
            if ($tasaCambio->ANULADO === null) {
                $tasaCambio->ANULADO = 0;
            }

            $tasaCambio->save();

            return redirect::to('monedas/' . $request->MONEDA_ID . '/edit');
        }


    }

    public function edit($id)
    {
        $tasaCambio = TasaCambio::findOrFail($id);

        return view('monedas.tasaCambio.edit', compact('tasaCambio'));
    }

    public function update(CreateTasaCambioRequest $request, $id)
    {
        $tasaCambio = TasaCambio::findOrFail($id);
        //$tasaCambio->fill($request->all());

        //dd($moneda);*/
        $tasaCambio->save();
        if ($request->ANULADO === null) {
            $request->ANULADO = 0;
        }

        TasaCambio::where('ID', $tasaCambio->ID)
          ->update(['FECHA' => $request->FECHA, 'COMPRA' => $request->COMPRA, 'ANULADO' => $request->ANULADO]);
        //dd('se supone que ya grabo');
        return Redirect::to('monedas/' . $tasaCambio->MONEDA_ID . '/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function anularTasaCambio($id)
    {

        $param = explode('-', $id);
        $tasa_id = $param[0];
        $moneda_id = $param[1];

        $anulado = TasaCambio::where('id', '=', $tasa_id)->pluck('anulado');
       
        if ($anulado == 1) {
            TasaCambio::where('id', $tasa_id)
                        ->update(['ANULADO' => 0]);
            $anular = 'No';
        } else {
            TasaCambio::where('id', $tasa_id)
            ->update(['ANULADO' => 1]);            
            $anular = 'Si';
        }        
        return $anular;        
    }

    public function verificaFecha($id)
    {

        $param = explode('&', $id);
        $fecha = $param[0];
        $moneda_id = $param[1];

        $existe = TasaCambio::where('ID', '=', $moneda_id)->where('FECHA', '=', $fecha)->first();
        if($existe) {
            return 'Ya Existe';
        } else {
            return 'No existe';
        }


    }
}

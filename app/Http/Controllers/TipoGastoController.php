<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\TipoGasto;
use App\Empresa;

class TipoGastoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function indexTipoGasto($id)
     {
         $tipoGastos = TipoGasto::select('*')
                             ->where('cat_tipogasto.EMPRESA_ID', '=', $id)
                             ->where('cat_tipogasto.ANULADO', '=', 0)
                             ->paginate(10);
         $empresa_id = $id;

         return view('tipoGastos.index', compact('tipoGastos', 'empresa_id'));
     }

     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function empresaCreateTipoGasto($id)
     {
         $empresa_id = $id;
         return view('tipoGastos.create', compact('empresa_id'));
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
        $empresa_id = $request->EMPRESA_ID;
        //dd($empresa_id);

        $tipoGasto = new TipoGasto();

        $tipoGasto->EMPRESA_ID = $empresa_id;
        $tipoGasto->DESCRIPCION = $request->DESCRIPCION;
        $tipoGasto->EXENTO = $request->EXENTO;
        $tipoGasto->CAUSAEXENCION_ID = $request->CAUSAEXENCION_ID;
        $tipoGasto->MONTO_A_APLICAR = $request->MONTO_A_APLICAR;
        $tipoGasto->CUENTA_CONTABLE_EXENTO = $request->CUENTA_CONTABLE_EXENTO;
        $tipoGasto->CODIGO_IMPUESTO_EXENTO = $request->CODIGO_IMPUESTO_EXENTO;
        $tipoGasto->CUENTA_CONTABLE_AFECTO = $request->CUENTA_CONTABLE_AFECTO;
        $tipoGasto->CODIGO_IMPUESTO_AFECTO = $request->CODIGO_IMPUESTO_AFECTO;
        $tipoGasto->CUENTA_CONTABLE_REMANENTE = $request->CUENTA_CONTABLE_AFECTO;
        $tipoGasto->CODIGO_IMPUESTO_REMANENTE = $request->CODIGO_IMPUESTO_REMANENTE;
        $tipoGasto->ANULADO = $request->ANULADO;

        if ($tipoGasto->ANULADO === null) {
            $tipoGasto->ANULADO = 0;
        }

        $tipoGasto->save();

        return redirect::to('empresas');
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
      $tipoGasto = TipoGasto::findOrFail($id);

      return view('tipoGastos.edit', compact('tipoGasto'));
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
      $tipoGasto = TipoGasto::findOrFail($id);
      //$moneda->fill($request->all());

      if ($request->ANULADO === null) {
          $request->ANULADO = 0;
      }
      
      $tipoGasto::where('ID', $tipoGasto->ID)
              ->update(['DESCRIPCION' => $request->DESCRIPCION, 'EXENTO' => $request->EXENTO, 'MONTO_A_APLICAR' => $request->MONTO_A_APLICAR,
                        'CAUSAEXENCION_ID' => $request->CAUSAEXENCION_ID, 'CUENTA_CONTABLE_EXENTO' => $request->CUENTA_CONTABLE_EXENTO, 'CODIGO_IMPUESTO_EXENTO' => $request->CODIGO_IMPUESTO_EXENTO,
                        'CUENTA_CONTABLE_AFECTO' => $request->CUENTA_CONTABLE_AFECTO, 'CODIGO_IMPUESTO_AFECTO' => $request->CODIGO_IMPUESTO_AFECTO,
                        'CUENTA_CONTABLE_REMANENTE' => $request->CUENTA_CONTABLE_REMANENTE, 'CODIGO_IMPUESTO_REMANENTE' => $request->CODIGO_IMPUESTO_REMANENTE, 'ANULADO' => $request->ANULADO]);

      return Redirect::to('empresas');
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
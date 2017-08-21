<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTipoGastoRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\TipoGasto;
use App\Empresa;

class TipoGastoController extends Controller
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
     public function indexTipoGasto($id)
     {
         $tipoGastos = TipoGasto::select('*')
                             ->where('cat_tipogasto.EMPRESA_ID', '=', $id)
                             ->where('cat_tipogasto.ANULADO', '=', 0)
                             ->paginate(10);
         $empresa_id = $id;
         $nombreEmpresa = Empresa::select('DESCRIPCION')->where('ID', '=', $id)->first();

         return view('tipoGastos.index', compact('tipoGastos', 'empresa_id', 'nombreEmpresa'));
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
    public function store(CreateTipoGastoRequest $request)
    {   dd($request->all());
        $empresa_id = $request->EMPRESA_ID;

        $tipoGasto = new TipoGasto();

        $tipoGasto->EMPRESA_ID = $empresa_id;
        $tipoGasto->DESCRIPCION = $request->DESCRIPCION;
        $tipoGasto->EXENTO = $request->EXENTO;
        $tipoGasto->MONTO_A_APLICAR = ($request->MONTO_A_APLICAR_CANTIDAD) ? $request->MONTO_A_APLICAR_CANTIDAD : $request->MONTO_A_APLICAR_PORCENTAJE;
        $tipoGasto->CAUSAEXENCION_ID = $request->CAUSAEXENCION_ID;
        $tipoGasto->UNIDAD_MEDIDA = $request->UNIDAD_MEDIDA;
        $tipoGasto->OPCIONKILOMETRAJE_ID = $request->OPCIONKILOMETRAJE_ID;
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

        return redirect::to('empresa/tipoGasto/' . $empresa_id);
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
      //dd($tipoGasto->CAUSAEXENCION_ID);

      if($tipoGasto->CAUSAEXENCION_ID == 2){
          $tipoGasto->MONTO_A_APLICAR_CANTIDAD = $tipoGasto->MONTO_A_APLICAR;
      } else {
          $tipoGasto->MONTO_A_APLICAR_PORCENTAJE = $tipoGasto->MONTO_A_APLICAR;
      }

      return view('tipoGastos.edit', compact('tipoGasto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateTipoGastoRequest $request, $id)
    {
      $tipoGasto = TipoGasto::findOrFail($id);
      //$moneda->fill($request->all());
      //$
      $montoAplicar = ($request->MONTO_A_APLICAR_CANTIDAD) ? $request->MONTO_A_APLICAR_CANTIDAD : $request->MONTO_A_APLICAR_PORCENTAJE;

      if ($request->ANULADO === null) {
          $request->ANULADO = 0;
      }

      $tipoGasto::where('ID', $tipoGasto->ID)
              ->update(['DESCRIPCION' => $request->DESCRIPCION, 'EXENTO' => $request->EXENTO, 'MONTO_A_APLICAR' => $montoAplicar, 'UNIDAD_MEDIDA' => $request->UNIDAD_MEDIDA,
                        'CAUSAEXENCION_ID' => $request->CAUSAEXENCION_ID, 'CUENTA_CONTABLE_EXENTO' => $request->CUENTA_CONTABLE_EXENTO, 'CODIGO_IMPUESTO_EXENTO' => $request->CODIGO_IMPUESTO_EXENTO,
                        'CUENTA_CONTABLE_AFECTO' => $request->CUENTA_CONTABLE_AFECTO, 'CODIGO_IMPUESTO_AFECTO' => $request->CODIGO_IMPUESTO_AFECTO, 'OPCIONKILOMETRAJE_ID' => $request->OPCIONKILOMETRAJE_ID,
                        'CUENTA_CONTABLE_REMANENTE' => $request->CUENTA_CONTABLE_REMANENTE, 'CODIGO_IMPUESTO_REMANENTE' => $request->CODIGO_IMPUESTO_REMANENTE, 'ANULADO' => $request->ANULADO]);

      return redirect::to('empresa/tipoGasto/' . $tipoGasto->EMPRESA_ID);
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
     * Anule the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function anular($id)
    {
        $param = explode('-', $id);
        $id = $param[0];
        $empresa_id = $param[1];
        TipoGasto::where('ID', $id)
            ->update(['ANULADO' => 1]);

        return 1; //Redirect::to('empresa/tipoGasto/' . $empresa_id);
    }
}

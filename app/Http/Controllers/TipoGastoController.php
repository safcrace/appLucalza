<?php

namespace App\Http\Controllers;

use App\GrupoTipoGasto;
use App\Http\Requests\CreateTipoGastoRequest;
use App\SubcategoriaTipoGasto;
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

         $grupo = GrupoTipoGasto::lists('DESCRIPCION', 'ID')->toArray();

         return view('tipoGastos.create', compact('empresa_id', 'grupo'));
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
    {   //dd($request->all());
        $empresa_id = $request->EMPRESA_ID;

        $tipoGasto = new TipoGasto();

        $tipoGasto->EMPRESA_ID = $empresa_id;
        $tipoGasto->DESCRIPCION = $request->DESCRIPCION;
        //$tipoGasto->EXENTO = $request->EXENTO;
        //$tipoGasto->MONTO_A_APLICAR = ($request->MONTO_A_APLICAR_CANTIDAD) ? $request->MONTO_A_APLICAR_CANTIDAD : $request->MONTO_A_APLICAR_PORCENTAJE;
        //$tipoGasto->CAUSAEXENCION_ID = $request->CAUSAEXENCION_ID;
        //$tipoGasto->UNIDAD_MEDIDA = $request->UNIDAD_MEDIDA;
        $tipoGasto->GRUPOTIPOGASTO_ID = $request->GRUPO_ID;
        $tipoGasto->OPCIONCOMBUSTIBLE_ID = $request->OPCIONCOMBUSTIBLE_ID;
        $tipoGasto->OPCIONKILOMETRAJE_ID = $request->OPCIONKILOMETRAJE_ID;
        //$tipoGasto->ASIGNACIONPRESUPUESTO_ID = $request->ASIGNACIONPRESUPUESTO_ID;
        $tipoGasto->CUENTA_CONTABLE_EXENTO = $request->CUENTA_CONTABLE_EXENTO;
        $tipoGasto->DESCRIPCION_CUENTA_EXENTO = trim($request->CUENTA_EXENTO, "\t.");
        $tipoGasto->CODIGO_IMPUESTO_EXENTO = $request->CODIGO_IMPUESTO_EXENTO;
        $tipoGasto->DESCRIPCION_IMPUESTO_EXENTO = $request->IMPUESTO_EXENTO;
        $tipoGasto->CUENTA_CONTABLE_AFECTO = $request->CUENTA_CONTABLE_AFECTO;
        $tipoGasto->DESCRIPCION_CUENTA_AFECTO = trim($request->CUENTA_AFECTO, "\t.");
        $tipoGasto->CODIGO_IMPUESTO_AFECTO = $request->CODIGO_IMPUESTO_AFECTO;
        $tipoGasto->DESCRIPCION_IMPUESTO_AFECTO = $request->IMPUESTO_AFECTO;
        $tipoGasto->CUENTA_CONTABLE_REMANENTE = $request->CUENTA_CONTABLE_AFECTO;
        $tipoGasto->DESCRIPCION_CUENTA_REMANENTE = trim($request->CUENTA_REMANENTE, "\t.");
        $tipoGasto->CODIGO_IMPUESTO_REMANENTE = $request->CODIGO_IMPUESTO_REMANENTE;
        $tipoGasto->DESCRIPCION_IMPUESTO_REMANENTE = $request->IMPUESTO_REMANENTE;
        $tipoGasto->ANULADO = $request->ANULADO;

        if ($tipoGasto->ANULADO === null) {
            $tipoGasto->ANULADO = 0;
        }
//dd($tipoGasto);
        $tipoGasto->save();

        return redirect::to('tipoGastos/' . $tipoGasto->id . '/edit');
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
      //dd($tipoGasto);//->GRUPOTIPOGASTO_ID);

      $subcategoriaTipoGasto = SubcategoriaTipoGasto::select('ID', 'DESCRIPCION', 'EXENTO')
                                                            ->where('ANULADO', '=', 0)
                                                            ->where('TIPOGASTO_ID', '=', $id)
                                                            ->paginate(10);

      //dd($subcategoriaTipoGasto);

     /* $tasaCambio = TasaCambio::select('cat_tasacambio.ID', 'cat_tasacambio.MONEDA_ID', 'cat_tasacambio.FECHA', 'cat_tasacambio.COMPRA', 'cat_tasacambio.ANULADO')
          ->where('cat_tasacambio.ANULADO', '=', 0)
          ->where('cat_tasacambio.MONEDA_ID', '=', $id)->paginate(4);*/

      if($tipoGasto->CAUSAEXENCION_ID == 2){
          $tipoGasto->MONTO_A_APLICAR_CANTIDAD = $tipoGasto->MONTO_A_APLICAR;
      } else {
          $tipoGasto->MONTO_A_APLICAR_PORCENTAJE = $tipoGasto->MONTO_A_APLICAR;
      }

      $grupo = GrupoTipoGasto::lists('DESCRIPCION', 'ID')->toArray();

      return view('tipoGastos.edit', compact('tipoGasto', 'grupo', 'subcategoriaTipoGasto'));
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
//dd($request->all());
      //$moneda->fill($request->all());
      //$
      $montoAplicar = ($request->MONTO_A_APLICAR_CANTIDAD) ? $request->MONTO_A_APLICAR_CANTIDAD : $request->MONTO_A_APLICAR_PORCENTAJE;

      if ($request->ANULADO === null) {
          $request->ANULADO = 0;
      }

      $tipoGasto::where('ID', $tipoGasto->ID)
              ->update(['DESCRIPCION' => $request->DESCRIPCION, 'GRUPOTIPOGASTO_ID' => $request->GRUPO_ID, 'OPCIONCOMBUSTIBLE_ID' => $request->OPCIONCOMBUSTIBLE_ID,
                        'OPCIONKILOMETRAJE_ID' => $request->OPCIONKILOMETRAJE_ID,
                        'CUENTA_CONTABLE_EXENTO' => $request->CUENTA_CONTABLE_EXENTO,  'DESCRIPCION_CUENTA_EXENTO' => trim($request->CUENTA_EXENTO, "\t."),
                        'CODIGO_IMPUESTO_EXENTO' => $request->CODIGO_IMPUESTO_EXENTO, 'DESCRIPCION_IMPUESTO_EXENTO' => $request->IMPUESTO_EXENTO,
                        'CUENTA_CONTABLE_AFECTO' => $request->CUENTA_CONTABLE_AFECTO, 'DESCRIPCION_CUENTA_AFECTO' => trim($request->CUENTA_AFECTO, "\t."),
                        'CODIGO_IMPUESTO_AFECTO' => $request->CODIGO_IMPUESTO_AFECTO, 'DESCRIPCION_IMPUESTO_AFECTO' => $request->IMPUESTO_AFECTO,
                        'CUENTA_CONTABLE_REMANENTE' => $request->CUENTA_CONTABLE_REMANENTE, 'DESCRIPCION_CUENTA_REMANENTE' => trim($request->CUENTA_REMANENTE, "\t."),
                        'CODIGO_IMPUESTO_REMANENTE' => $request->CODIGO_IMPUESTO_REMANENTE, 'DESCRIPCION_IMPUESTO_REMANENTE' => $request->IMPUESTO_REMANENTE, 'ANULADO' => $request->ANULADO
                        ]);

      return redirect::to('empresa/tipoGasto/' . $tipoGasto->EMPRESA_ID);
    }

    public function getTipoGasto($id)
    {
        return TipoGasto::select('ID', 'DESCRIPCION')
                                ->where('ID', '=', $id)
                                ->get();
    }

    public function getSubcategoriaTipoGasto($id)
    {
        return SubcategoriaTipoGasto::select('ID', 'DESCRIPCION')
            ->where('TIPOGASTO_ID', '=', $id)
            ->get();
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

<?php

namespace App\Http\Controllers;

use App\SubcategoriaTipoGasto;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class SubcategoriaTipoGastoController extends Controller
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
    public function createSubcategoria($id)
    {
        $tipoGasto_id = $id;
        return view('tipoGastos.categorias.create', compact('tipoGasto_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   //dd($request->all());
        $tipoGasto = $request->TIPOGASTO_ID;


        $subcategoriaTipoGasto = new SubcategoriaTipoGasto();

        $subcategoriaTipoGasto->TIPOGASTO_ID = $tipoGasto;
        $subcategoriaTipoGasto->DESCRIPCION = $request->DESCRIPCION;
        $subcategoriaTipoGasto->EXENTO = $request->EXENTO;
        $subcategoriaTipoGasto->MONTO_A_APLICAR = ($request->MONTO_A_APLICAR_CANTIDAD) ? $request->MONTO_A_APLICAR_CANTIDAD : $request->MONTO_A_APLICAR_PORCENTAJE;
        $subcategoriaTipoGasto->CAUSAEXENCION_ID = $request->CAUSAEXENCION_ID;
        $subcategoriaTipoGasto->UNIDAD_MEDIDA = $request->UNIDAD_MEDIDA;
        $subcategoriaTipoGasto->ANULADO = $request->ANULADO;

        if ($subcategoriaTipoGasto->ANULADO === null) {
            $subcategoriaTipoGasto->ANULADO = 0;
        }

        $subcategoriaTipoGasto->save();

        return redirect::to('tipoGastos/' . $tipoGasto. '/edit');
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
        $subcategoriaTipoGastos = SubcategoriaTipoGasto::findOrFail($id);

        if($subcategoriaTipoGastos->CAUSAEXENCION_ID == 2){
            $subcategoriaTipoGastos->MONTO_A_APLICAR_CANTIDAD = $subcategoriaTipoGastos->MONTO_A_APLICAR;
        } else {
            $subcategoriaTipoGastos->MONTO_A_APLICAR_PORCENTAJE = $subcategoriaTipoGastos->MONTO_A_APLICAR;
        }
//dd($subcategoriaTipoGastos);
        return view('tipoGastos.categorias.edit', compact('subcategoriaTipoGastos'));
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
        $subcategoriaTipoGastos = SubcategoriaTipoGasto::findOrFail($id);

        $montoAplicar = ($request->MONTO_A_APLICAR_CANTIDAD) ? $request->MONTO_A_APLICAR_CANTIDAD : $request->MONTO_A_APLICAR_PORCENTAJE;

        if ($request->ANULADO === null) {
            $request->ANULADO = 0;
        }

        SubcategoriaTipoGasto::where('ID', $id)
            ->update(['DESCRIPCION' => $request->DESCRIPCION, 'EXENTO' => $request->EXENTO, 'MONTO_A_APLICAR' => $montoAplicar,
                'CAUSAEXENCION_ID' => $request->CAUSAEXENCION_ID, 'UNIDAD_MEDIDA' => $request->UNIDAD_MEDIDA,  'ANULADO' => $request->ANULADO
            ]);

        return redirect::to('tipoGastos/' . $subcategoriaTipoGastos->TIPOGASTO_ID . '/edit');
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

    public function anularSubcategoriaTipoGasto($id)
    {
        $param = explode('-', $id);
        $id = $param[0];
        //$empresa_id = $param[1];
        SubcategoriaTipoGasto::where('ID', $id)
            ->update(['ANULADO' => 1]);

        return 1; //Redirect::to('empresa/tipoGasto/' . $empresa_id);
    }
}

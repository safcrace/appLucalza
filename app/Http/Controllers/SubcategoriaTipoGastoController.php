<?php

namespace App\Http\Controllers;

use App\SubcategoriaTipoGasto;
use App\TipoAsignacion;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class SubcategoriaTipoGastoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('roles:superAdmin,master,vendedor,administrador');
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
    public function createSubcategoria($id)
    {

        $tipoGasto_id = $id;
        $tipoAsignacion = TipoAsignacion::lists('DESCRIPCION', 'ID')
            ->toArray();
        return view('tipoGastos.categorias.create', compact('tipoGasto_id', 'tipoAsignacion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->validate($request, [
            'DESCRIPCION' => 'required',
            'EXENTO'    => 'required'
        ]);
        $tipoGasto = $request->TIPOGASTO_ID;


        $subcategoriaTipoGasto = new SubcategoriaTipoGasto();

        $subcategoriaTipoGasto->TIPOGASTO_ID = $tipoGasto;
        $subcategoriaTipoGasto->DESCRIPCION = $request->DESCRIPCION;
        $subcategoriaTipoGasto->EXENTO = $request->EXENTO;
        $subcategoriaTipoGasto->MONTO_A_APLICAR = ($request->MONTO_A_APLICAR_CANTIDAD) ? $request->MONTO_A_APLICAR_CANTIDAD : $request->MONTO_A_APLICAR_PORCENTAJE;
        $subcategoriaTipoGasto->CAUSAEXENCION_ID = $request->CAUSAEXENCION_ID;
        $subcategoriaTipoGasto->UNIDAD_MEDIDA_ID = ($request->UNIDAD_MEDIDA_ID) ? $request->UNIDAD_MEDIDA_ID : 1;
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

        $tipoAsignacion = TipoAsignacion::lists('DESCRIPCION', 'ID')
            ->toArray();
//dd($subcategoriaTipoGastos);
        return view('tipoGastos.categorias.edit', compact('subcategoriaTipoGastos', 'tipoAsignacion'));
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
                'CAUSAEXENCION_ID' => $request->CAUSAEXENCION_ID, 'UNIDAD_MEDIDA_ID' => $request->UNIDAD_MEDIDA_ID,  'ANULADO' => $request->ANULADO
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
        $anulado = SubcategoriaTipoGasto::where('id', '=', $id)->pluck('anulado');
       
        if ($anulado == 1) {
            SubcategoriaTipoGasto::where('id', $id)
                        ->update(['ANULADO' => 0]);
            $anular = 'No';
        } else {
            SubcategoriaTipoGasto::where('id', $id)
            ->update(['ANULADO' => 1]);            
            $anular = 'Si';
        }        
        return $anular;         
    }
}

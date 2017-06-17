<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Proveedor;
use App\Empresa;


class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function indexProveedor($id)
     {
         $proveedores = Proveedor::select('*')
                             ->where('cat_proveedor.EMPRESA_ID', '=', $id)
                             ->where('cat_proveedor.ANULADO', '=', 0)
                             ->paginate(10);
         $empresa_id = $id;

         return view('proveedores.index', compact('proveedores', 'empresa_id'));
     }

     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function empresaCreateProveedor($id)
     {
         $empresa_id = $id;
         return view('proveedores.create', compact('empresa_id'));
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

        $proveedor = new Proveedor();

        $proveedor->EMPRESA_ID = $empresa_id;
        $proveedor->MONEDA_ID = $request->MONEDA_ID;
        $proveedor->IDENTIFICADOR_TRIBUTARIO = $request->IDENTIFICADOR_TRIBUTARIO;
        $proveedor->NOMBRE = $request->NOMBRE;
        $proveedor->DOMICILIO = $request->DOMICILIO;
        $proveedor->ANULADO = $request->ANULADO;

        if ($proveedor->ANULADO === null) {
            $proveedor->ANULADO = 0;
        }

        $proveedor->save();

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
        $proveedor = Proveedor::findOrFail($id);

        return view('proveedores.edit', compact('proveedor'));
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
        $proveedor = Proveedor::findOrFail($id);
        //$moneda->fill($request->all());

        if ($request->ANULADO === null) {
            $request->ANULADO = 0;
        }

        Proveedor::where('ID', $proveedor->ID)
                ->update(['MONEDA_ID' => $request->MONEDA_ID, 'IDENTIFICADOR_TRIBUTARIO' => $request->IDENTIFICADOR_TRIBUTARIO, 'NOMBRE' => $request->NOMBRE,
                          'DOMICILIO' => $request->DOMICILIO, 'ANULADO' => $request->ANULADO]);

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

    public function anular($id)
    {
        Proveedor::where('ID', $id)
                ->update(['ANULADO' => 1]);

        return Redirect::to('empresas');
    }
}

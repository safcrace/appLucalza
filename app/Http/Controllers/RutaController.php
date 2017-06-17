<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Ruta;
use App\Empresa;
use App\UsuarioEmpresa;

class RutaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function indexRuta($id)
     {
         $rutas = Ruta::select('*')
                             ->where('cat_ruta.EMPRESA_ID', '=', $id)
                             ->where('cat_ruta.ANULADO', '=', 0)
                             ->paginate(10);
         $empresa_id = $id;

         return view('rutas.index', compact('rutas', 'empresa_id'));
     }

     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function empresaCreateRuta($id)
     {
         $empresa_id = $id;
         return view('rutas.create', compact('empresa_id'));
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

        $ruta = new Ruta();

        $ruta->EMPRESA_ID = $empresa_id;
        $ruta->CLAVE = $request->CLAVE;
        $ruta->DESCRIPCION = $request->DESCRIPCION;
        $ruta->ANULADO = $request->ANULADO;

        if ($ruta->ANULADO === null) {
            $ruta->ANULADO = 0;
        }

        $ruta->save();

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
        $ruta = Ruta::findOrFail($id);

        return view('rutas.edit', compact('ruta'));
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
        $ruta = Ruta::findOrFail($id);
        //$moneda->fill($request->all());

        if ($request->ANULADO === null) {
            $request->ANULADO = 0;
        }

        Ruta::where('ID', $ruta->ID)
                ->update(['CLAVE' => $request->CLAVE, 'DESCRIPCION' => $request->DESCRIPCION, 'ANULADO' => $request->ANULADO]);

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

    /**
     * Anule the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function anular($id)
    {
        Ruta::where('ID', $id)
                ->update(['ANULADO' => 1]);

        return Redirect::to('empresas');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Empresa;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas = Empresa::select('*')
                            ->where('cat_empresa.ANULADO', '=', 0)
                            ->paginate(10);

        return view('empresas.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('empresas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empresa = new Empresa();
        $empresa->CLAVE = $request->CLAVE;
        $empresa->DESCRIPCION = $request->DESCRIPCION;
        $empresa->ANULADO = $request->ANULADO;
        $empresa->LICENSESERVER = $request->LICENSESERVER;
        $empresa->USERSAP = $request->USERSAP;
        $empresa->PASSSAP = bcrypt($request->PASSSAP);
        $empresa->DBSAP = $request->DBSAP;
        $empresa->USERSQL = $request->USERSQL;
        $empresa->PASSSQL = bcrypt($request->PASSSQL);
        $empresa->SERVIDORSQL = $request->SERVIDORSQL;
        $empresa->SAPDBTYPE = $request->SAPDBTYPE;

        $empresa->save();

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
      $empresa = Empresa::findOrFail($id);

      return view('empresas.edit', compact('empresa'));
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
        $empresa = Empresa::findOrFail($id);
        //$moneda->fill($request->all());
        //$moneda->ID = $request->ID;

        Empresa::where('ID', $empresa->ID)
          ->update(['CLAVE' => $request->CLAVE, 'DESCRIPCION' => $request->DESCRIPCION, 'ANULADO' => $request->ANULADO, 'LICENSESERVER' => $request->LICENSESERVER,
                    'USERSAP' => $request->USERSAP, 'PASSSAP' => bcrypt($request->PASSSAP), 'DBSAP' => $request->DBSAP, 'USERSQL' => $request->USERSQL,
                    'PASSSQL' => bcrypt($request->ANULADO), 'SERVIDORSQL' => $request->SERVIDORSQL, 'SAPDBTYPE' => $request->SAPDBTYPE,]);
        //dd('se supone que ya grabo');
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function anular($id)
    {
        Empresa::where('ID', $id)
                ->update(['ANULADO' => 1]);

        return Redirect::to('empresas');
    }

}

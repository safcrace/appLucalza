<?php

namespace App\Http\Controllers;

use App\SapDbType;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\CreateEmpresaRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Empresa;
use App\Moneda;
use Illuminate\Support\Facades\Session;

class EmpresaController extends Controller
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
    public function index(Request $request)
    {
        //$user = $request->user();
        //dd($user->can('ver usuarios'));



        if (auth()->user()->hasRole('administrador')) {
            $empresa_id = Session::get('empresa');
            $empresas = Empresa::select('*')
                ->where('ANULADO', '=', 0)
                ->where('ID', '=', $empresa_id)
                ->paginate(10);

            return view('empresas.index', compact('empresas'));

        }

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
        $moneda = Moneda::where('ANULADO', '=', 0)
                                        ->lists('DESCRIPCION', 'ID')
                                        ->toArray();

        $sapDbType = SapDbType::lists('DESCRIPCION', 'ID_DATASERVERTYPE')->toArray();

        return view('empresas.create', compact('moneda','sapDbType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEmpresaRequest $request)
    {
        $empresa = new Empresa();
        $empresa->CLAVE = $request->CLAVE;
        $empresa->DESCRIPCION = $request->DESCRIPCION;
        $empresa->MONEDA_ID = $request->MONEDA_ID;
        $empresa->IMPUESTO = $request->IMPUESTO;
        $empresa->ANULADO = $request->ANULADO;
        $empresa->LICENSESERVER = $request->LICENSESERVER;
        $empresa->USERSAP = $request->USERSAP;
        $empresa->PASSSAP = bcrypt($request->PASSSAP);
        $empresa->DBSAP = $request->DBSAP;
        $empresa->USERSQL = $request->USERSQL;
        $empresa->PASSSQL = bcrypt($request->PASSSQL);
        $empresa->SERVIDORSQL = $request->SERVIDORSQL;
        $empresa->ID_DATASERVERTYPE = $request->ID_DATASERVERTYPE;
        $empresa->TIEMPOATRASO_RUTAS = $request->TIEMPOATRASO_RUTAS;
        $empresa->TIEMPOATRASO_OTROSGASTOS = $request->TIEMPOATRASO_OTROSGASTOS;


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

      $moneda = Moneda::where('ANULADO', '=', 0)
                             ->lists('DESCRIPCION', 'ID')
                             ->toArray();

        $sapDbType = SapDbType::lists('DESCRIPCION', 'ID_DATASERVERTYPE')->toArray();

      return view('empresas.edit', compact('empresa', 'moneda', 'sapDbType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateEmpresaRequest $request, $id)
    {
        $empresa = Empresa::findOrFail($id);
        //$moneda->fill($request->all());

        Empresa::where('ID', $empresa->ID)
          ->update(['CLAVE' => $request->CLAVE, 'DESCRIPCION' => $request->DESCRIPCION, 'ANULADO' => $request->ANULADO, 'LICENSESERVER' => $request->LICENSESERVER,
                    'TIEMPOATRASO_RUTAS' => $request->TIEMPOATRASO_RUTAS, 'TIEMPOATRASO_OTROSGASTOS' => $request->TIEMPOATRASO_OTROSGASTOS, 'USERSAP' => $request->USERSAP,
                    'PASSSAP' => bcrypt($request->PASSSAP), 'DBSAP' => $request->DBSAP, 'USERSQL' => $request->USERSQL, 'PASSSQL' => bcrypt($request->ANULADO),
                    'SERVIDORSQL' => $request->SERVIDORSQL, 'ID_DATASERVERTYPE' => $request->ID_DATASERVERTYPE, 'MONEDA_ID' => $request->MONEDA_ID, 'IMPUESTO' => $request->IMPUESTO]);

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

        return 1;//Redirect::to('empresas');
    }

}

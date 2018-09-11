<?php

namespace App\Http\Controllers;

use App\SapDbType;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\CreateEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Empresa;
use Illuminate\Support\Facades\Session;

class EmpresaController extends Controller
{

    public function __construct()
    {
        echo('paso1');
        $this->middleware('auth');
        echo('paso2');
        $this->middleware('roles:superAdmin,master,administrador');
        
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
            
        //dd('llego');
        
        if (auth()->user()->hasRole('administrador')) {
            $empresa_id = Session::get('empresa');
            $empresas = Empresa::select('*')                
                ->where('ID', '=', $empresa_id)
                ->paginate(10);

            return view('empresas.index', compact('empresas'));

        }

        $empresas = Empresa::select('*')                            
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
        /** Se verifica que no exista Codigo de Empresa */
        $existeCodigo = Empresa::select('ID')->where('CLAVE', '=', $request->CLAVE)->first();
        
        if ($existeCodigo) {
            return back()->withInput()->with('info', 'El Código ya existe en la Base de Datos.');
        }

        $empresa = new Empresa();
        $empresa->CLAVE = $request->CLAVE;
        $empresa->DESCRIPCION = $request->DESCRIPCION;
        $empresa->MONEDA_LOCAL = $request->MONEDA_LOCAL;
        $empresa->MONEDA_SYS = $request->MONEDA_SYS;        
        $empresa->IMPUESTO = $request->IMPUESTO;
        $empresa->ANULADO = $request->ANULADO;
        $empresa->LICENSESERVER = $request->LICENSESERVER;
        $empresa->USERSAP = $request->USERSAP;
        $empresa->PASSSAP = bcrypt($request->PASSSAP);
        //$empresa->PASSSAP = encrypt('$request->PASSSAP');
        //echo ('Vamo a Ver... ' . $empresa->PASSSAP . '<br>');
        //dd(decrypt('pJ1mxhsxHYdqIdaYtrGjlw=='));
        $empresa->DBSAP = $request->DBSAP;
        $empresa->FILAS_NOTA_CREDITO = $request->FILAS_NOTA_CREDITO;
        $empresa->USERSQL = $request->USERSQL;
        $empresa->PASSSQL = $request->PASSSQL; //bcrypt($request->PASSSQL);
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

        $sapDbType = SapDbType::lists('DESCRIPCION', 'ID_DATASERVERTYPE')->toArray();

      return view('empresas.edit', compact('empresa', 'sapDbType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmpresaRequest $request, $id)
    {   //dd($id);
        $empresa = Empresa::findOrFail($id);
        //$moneda->fill($request->all());
        //dd($request->ANULADO);
        
        if ($request->PASSSAP == null) {
            $passwordSap = Empresa::where('id', '=', $id)->pluck('PASSSAP');
            
        } else {
            $passwordSap = bcrypt($request->PASSSAP);
        }
        if ($request->PASSSQL == null) {
            $passwordSql = Empresa::where('id', '=', $id)->pluck('PASSSQL');
            
        } else {
            $passwordSql = $request->PASSSQL; //bcrypt($request->PASSSQL);
        }
        

        Empresa::where('ID', $empresa->ID)
          ->update(['CLAVE' => $request->CLAVE, 'DESCRIPCION' => $request->DESCRIPCION, 'ANULADO' => $request->ANULADO, 'LICENSESERVER' => $request->LICENSESERVER,
                    'TIEMPOATRASO_RUTAS' => $request->TIEMPOATRASO_RUTAS, 'TIEMPOATRASO_OTROSGASTOS' => $request->TIEMPOATRASO_OTROSGASTOS, 'USERSAP' => $request->USERSAP,
                    'PASSSAP' => $passwordSap, 'DBSAP' => $request->DBSAP, 'USERSQL' => $request->USERSQL, 'PASSSQL' => $passwordSql,
                    'SERVIDORSQL' => $request->SERVIDORSQL, 'ID_DATASERVERTYPE' => $request->ID_DATASERVERTYPE, 
                    'IMPUESTO' => $request->IMPUESTO, 'FILAS_NOTA_CREDITO' => $request->FILAS_NOTA_CREDITO]);

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
        $anulado = Empresa::where('id', '=', $id)->pluck('anulado');
       
        if ($anulado == 1) {
            Empresa::where('id', $id)
                        ->update(['ANULADO' => 0]);
            $anular = 'No';
        } else {
            Empresa::where('id', $id)
            ->update(['ANULADO' => 1]);            
            $anular = 'Si';
        }        
        return $anular;             
    }

}

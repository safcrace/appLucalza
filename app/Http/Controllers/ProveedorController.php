<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProveedorRequest;
use App\TipoProveedor;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Proveedor;
use App\Empresa;
use Illuminate\Support\Facades\Session;


class ProveedorController extends Controller
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
     public function indexProveedor($id)
     {
         $proveedores = Proveedor::select('*')
                             ->where('cat_proveedor.EMPRESA_ID', '=', $id)
                             ->where('cat_proveedor.ANULADO', '=', 0)
                             ->paginate(10);
         $empresa_id = $id;

         $nombreEmpresa = Empresa::select('DESCRIPCION')->where('ID', '=', $empresa_id)->first();

         return view('proveedores.index', compact('proveedores', 'empresa_id', 'nombreEmpresa'));
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
         $tipoProveedor = TipoProveedor::lists('DESCRIPCION', 'ID')->toArray();

         //$empresa_id = Session::get('empresa');

         $moneda = Empresa::select('cat_moneda.ID', 'cat_moneda.DESCRIPCION')
             ->join('cat_moneda', 'cat_moneda.ID', '=', 'cat_empresa.MONEDA_ID')
             ->where('cat_empresa.ID', '=',  $empresa_id)
             ->first();

         return view('proveedores.create', compact('empresa_id', 'tipoProveedor', 'moneda'));
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
    public function store(CreateProveedorRequest $request)
    {
        //dd('esta llegando aqui');
        if(isset($request->EMPRESA_ID)) {
            $empresa_id = $request->EMPRESA_ID;
        } else {
            $empresa_id = Session::get('empresa');
        }

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

        if ($request->ajax()) {

            $nuevoProveedor[] = $proveedor->IDENTIFICADOR_TRIBUTARIO;
            $nuevoProveedor[] = $proveedor->NOMBRE;
            $nuevoProveedor[] = $proveedor->id;


            return  $nuevoProveedor;
        }


        return redirect::to('empresa/proveedor/' . $empresa_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $proveedores = Proveedor::orderBy('ID')->lists('IDENTIFICADOR_TRIBUTARIO', 'ID')->toArray();
        return ( compact('proveedores'));
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
        //dd($proveedor);
        $moneda = Empresa::select('cat_moneda.ID', 'cat_moneda.DESCRIPCION')
            ->join('cat_moneda', 'cat_moneda.ID', '=', 'cat_empresa.MONEDA_ID')
            ->where('cat_empresa.ID', '=',  $proveedor->EMPRESA_ID)
            ->first();

        $tipoProveedor = TipoProveedor::lists('DESCRIPCION', 'ID')->toArray();

        return view('proveedores.edit', compact('proveedor', 'moneda', 'tipoProveedor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateProveedorRequest $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        //$moneda->fill($request->all());

        if ($request->ANULADO === null) {
            $request->ANULADO = 0;
        }

        Proveedor::where('ID', $proveedor->ID)
                ->update(['MONEDA_ID' => $request->MONEDA_ID, 'IDENTIFICADOR_TRIBUTARIO' => $request->IDENTIFICADOR_TRIBUTARIO, 'NOMBRE' => $request->NOMBRE,
                          'DOMICILIO' => $request->DOMICILIO, 'ANULADO' => $request->ANULADO]);

        return redirect::to('empresa/proveedor/' . $proveedor->EMPRESA_ID);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizarProveedor($id)
    {
        $param = explode('-', $id);
        $tipoProveedor = $param[0];
        $proveedorId = $param[1];
        $liquidacionId = $param[2];

        Proveedor::where('ID', $proveedorId)
            ->update(['TIPOPROVEEDOR_ID' => $tipoProveedor]);

        return 'Actualización Realizada'; //redirect::to('contabilidad/show/' . $liquidacionId);
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
        $param = explode('-', $id);
        $id = $param[0];
        $empresa_id = $param[1];
        Proveedor::where('ID', $id)
                ->update(['ANULADO' => 1]);

        return 1; //Redirect::to('empresa/proveedor/' . $empresa_id);
    }
}

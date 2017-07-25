<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUsuarioRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App\Empresa;
use App\UsuarioEmpresa;
use App\SupervisorVendedor;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexUsuario($id)
    {
        $users = User::select('users.id', 'users.nombre', 'users.email', 'cat_usuarioempresa.EMPRESA_ID')
                            ->join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
                            ->where('users.anulado', '=', 0)
                            ->where('cat_usuarioempresa.EMPRESA_ID', '=', $id)
                            ->paginate(10);
        $empresa = $id;
        $nombreEmpresa = Empresa::select('DESCRIPCION')->where('ID', '=', $id)->first();

        return view('usuarios.index', compact('users', 'empresa', 'nombreEmpresa'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vendedoresSupervisor($id)
    {
        $users = User::select('users.nombre', 'users.email')
                            ->join('cat_supervisor_vendedor', 'cat_supervisor_vendedor.VENDEDOR_ID_USUARIO', '=', 'users.id')
                            //->where('users.anulado', '=', 0)
                            ->where('cat_supervisor_vendedor.SUPERVISOR_ID_USUARIO', '=', $id)
                            ->paginate(10);


        return view('equipos.vendedores', compact('users'));
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
    public function empresaCreateUsuario($id)
    {
        $empresa_id = $id;


        return view('usuarios.create', compact('empresa_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function asignaEquipo($id)
    {
        $empresa_id = $id;

        $supervisores = User::join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
                              ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
                              ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
                              ->where('cat_empresa.ID', '=', $id)
                              ->where('users_roles.role_id', '=', 5)
                              ->lists('users.nombre', 'users.id')
                              ->toArray();

        $vendedores = User::join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
                              ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
                              ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
                              ->where('cat_empresa.ID', '=', $id)
                              ->where('users_roles.role_id', '=', 7)
                              ->lists('users.nombre', 'users.id')
                              ->toArray();

        return view('equipos.asignacion', compact('empresa_id', 'supervisores', 'vendedores'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUsuarioRequest $request)
    {
        $empresa_id = $request->EMPRESA_ID;
        $codigoProveedorSap = $request->codigoProveedorSap;
        //dd($empresa_id);

        $usuario = new User();

        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->tel_codpais = $request->tel_codpais;
        $usuario->telefono = $request->telefono;
        $usuario->codigoProveedorSap = $request->codigoProveedorSap;
        $usuario->activo = $request->activo;
        $usuario->anulado = $request->anulado;

        if ($usuario->anulado === null) {
            $usuario->anulado = 0;
        }

        $usuario->save();

        UsuarioEmpresa::insert( ['USER_ID' => $usuario->id, 'EMPRESA_ID' => $empresa_id, 'CODIGO_PROVEEDOR_SAP' => $codigoProveedorSap, 'ANULADO' => 0] );

        return redirect::to('empresa/usuario/' . $empresa_id);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function creaEquipo(Request $request)
    {
        $supervisorVendedor = new SupervisorVendedor();

        $supervisorVendedor->SUPERVISOR_ID_USUARIO = $request->SUPERVISOR_ID;
        $supervisorVendedor->VENDEDOR_ID_USUARIO = $request->VENDEDOR_ID;

        $supervisorVendedor->save();

        return back()->withInput();
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
        $param = explode('-', $id);
        $empresa_id = $param[1];
        $usuario_id = $param[0];
        $usuario = User::findOrFail($usuario_id);


        return view('usuarios.edit', compact('usuario', 'empresa_id'));
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
        //$usuario = User::findOrFail($id);
        //$moneda->fill($request->all());    bcrypt($request->PASSSAP)
        $param = explode('-', $id);
        $empresa_id = $param[1];
        $usuario_id = $param[0];

        if ($request->anulado === null) {
            $request->anulado = 0;
        }

        User::where('ID', $usuario_id)
                ->update(['nombre' => $request->nombre, 'email' => $request->email, 'tel_codpais' => $request->tel_codpais, 'password' => bcrypt($request->password),
                          'telefono' => $request->telefono, 'codigoProveedorSap' => $request->codigoProveedorSap, 'activo' => $request->activo, 'anulado' => $request->anulado]);

        return redirect::to('empresa/usuario/' . $empresa_id);
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
        User::where('ID', $id)
                ->update(['ANULADO' => 1]);

        return Redirect::to('empresa/usuario/' . $empresa_id);
    }
}

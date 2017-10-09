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
use Illuminate\Support\Facades\Session;

class UsuarioController extends Controller
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
    public function index()
    {
        if (auth()->user()->hasRole('superAdmin', 'master')) {
            $users = User::select('*')
                ->where('users.anulado', '=', 0)
                ->paginate(10);

            return view('usuarios.index', compact('users'));
        } else {
            $empresa_id = Session::get('empresa');
            $users = User::select('users.id', 'users.nombre', 'users.email', 'cat_usuarioempresa.EMPRESA_ID')
                                ->join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
                                ->where('users.anulado', '=', 0)
                                ->where('cat_usuarioempresa.EMPRESA_ID', '=', $empresa_id)
                                ->paginate(10);


            return view('usuarios.index', compact('users'));
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vendedoresSupervisor($id)
    {
        $param = explode('-', $id);
        $supervisor_id = $param[0];
        $empresa_id = $param[1];

        $users = User::select('users.nombre', 'users.email', 'cat_supervisor_vendedor.ID_SUPERVISION')
                            ->join('cat_supervisor_vendedor', 'cat_supervisor_vendedor.VENDEDOR_ID_USUARIO', '=', 'users.id')
                            ->where('cat_supervisor_vendedor.ANULADO', '=', 0)
                            ->where('cat_supervisor_vendedor.SUPERVISOR_ID_USUARIO', '=', $supervisor_id)
                            ->paginate(10);


        return view('equipos.vendedores', compact('users','supervisor_id', 'empresa_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('usuarios.create' );
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
        //$usuario->codigoProveedorSap = $request->codigoProveedorSap;
        $usuario->activo = $request->activo;
        $usuario->anulado = $request->anulado;

        if ($usuario->anulado === null) {
            $usuario->anulado = 0;
        }

        $usuario->save();

        /** Esto debe ser reubicado queda pendiete
        UsuarioEmpresa::insert( ['USER_ID' => $usuario->id, 'EMPRESA_ID' => $empresa_id, 'CODIGO_PROVEEDOR_SAP' => $codigoProveedorSap, 'ANULADO' => 0] );
        **/

        return redirect::to('usuarios');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function creaEquipo(Request $request)
    {
        if(($request->SUPERVISOR_ID === '') || ($request->VENDEDOR_ID === '')) {
            Session::flash('validaVendedorSupervisor', '¡Los campos Supervisor y Vendedor son Obligatorios!');
            return back()->withInput();
        }

        $supervisorVendedor = new SupervisorVendedor();

        $existe = SupervisorVendedor::where('SUPERVISOR_ID_USUARIO', '=', $request->SUPERVISOR_ID)->where('VENDEDOR_ID_USUARIO', '=', $request->VENDEDOR_ID)->first();

        if($existe) {

            SupervisorVendedor::where('ID_SUPERVISION', '=', $existe->ID_SUPERVISION)
                ->update(['ANULADO' => 0]);

        } else {

            $supervisorVendedor->SUPERVISOR_ID_USUARIO = $request->SUPERVISOR_ID;
            $supervisorVendedor->VENDEDOR_ID_USUARIO = $request->VENDEDOR_ID;
            $supervisorVendedor->ANULADO = 0;

            $supervisorVendedor->save();
        }

        $supervisorId = $request->SUPERVISOR_ID;

        $empresa_id = $request->EMPRESA_ID;

        $supervisores = User::join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
            ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
            ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
            ->where('cat_empresa.ID', '=', $empresa_id)
            ->where('users_roles.role_id', '=', 5)
            ->lists('users.nombre', 'users.id')
            ->toArray();

        $vendedores = User::join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
            ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
            ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
            ->where('cat_empresa.ID', '=', $empresa_id)
            ->where('users_roles.role_id', '=', 7)
            ->lists('users.nombre', 'users.id')
            ->toArray();



        return view('equipos.asignacion', compact( 'supervisores', 'vendedores', 'supervisorId', 'empresa_id'));
    }

    public function createUsuarioEmpresa(Request $request)
    {
        $usuarios = User::where('anulado', '=', 0)->lists('nombre','id')->toArray();
        $empresas = Empresa::where('ANULADO', '=', 0)->lists('DESCRIPCION', 'ID')->toArray();
        return view('usuariosXempresa.asignacion', compact('usuarios', 'empresas'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function usuariosAsignadosEmpresa($id)
    {
        $empresas = Empresa::select('cat_empresa.DESCRIPCION', 'cat_usuarioempresa.ID', 'cat_usuarioempresa.CODIGO_PROVEEDOR_SAP', 'cat_usuarioempresa.USER_ID')
                            ->join('cat_usuarioempresa', 'cat_usuarioempresa.EMPRESA_ID', '=', 'cat_empresa.id')
                            ->where('cat_usuarioempresa.USER_ID', '=', $id)
                            ->where('cat_usuarioempresa.ANULADO', '=', 0)
                            ->paginate(10);


        return view('usuariosXempresa.empresasAsignadas', compact('empresas'));
    }

    public function storeUsuarioEmpresa(Request $request)
    {

        if(($request->EMPRESA_ID === '') || ($request->codigoProveedorSap === '')) {
            Session::flash('validaUsuarioEmpresa', '¡Los campos Empresa y Código Proveedor SAP son Obligatorios!');
            return back()->withInput();
        }

        $usuarioEmpresa = new UsuarioEmpresa();

        $existe = UsuarioEmpresa::where('USER_ID', '=', $request->USUARIO_ID)->where('EMPRESA_ID', '=', $request->EMPRESA_ID)->first();

        if($existe) {

            UsuarioEmpresa::where('USER_ID', '=', $request->USUARIO_ID)->where('EMPRESA_ID', '=', $request->EMPRESA_ID)
                                ->update(['ANULADO' => 0]);

        } else {

            $usuarioEmpresa->USER_ID = $request->USUARIO_ID;
            $usuarioEmpresa->EMPRESA_ID = $request->EMPRESA_ID;
            $usuarioEmpresa->CODIGO_PROVEEDOR_SAP = $request->codigoProveedorSap;
            $usuarioEmpresa->DESCRIPCION_PROVEEDORSAP = $request->DESCRIPCION_PROVEEDORSAP;
            $usuarioEmpresa->ANULADO = 0;

            $usuarioEmpresa->save();
        }




        return redirect::back()->withInput();

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
        $usuario = User::findOrFail($id);


        return view('usuarios.edit', compact('usuario'));
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
        $usuario_id = $id;

        if ($request->anulado === null) {
            $request->anulado = 0;
        }

        User::where('ID', $usuario_id)
                ->update(['nombre' => $request->nombre, 'email' => $request->email, 'tel_codpais' => $request->tel_codpais, 'password' => bcrypt($request->password),
                          'telefono' => $request->telefono, 'activo' => $request->activo, 'anulado' => $request->anulado]);

        return redirect::to('usuarios');
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

        User::where('id', $id)
                ->update(['ANULADO' => 1]);

        return 1;//Redirect::to('empresa/usuario/' . $empresa_id);
    }

    public function anularUsuarioEmpresa($id)
    {
        $param = explode('-', $id);
        $usuarioEmpresa_id = $param[0];
        $usuario_id = $param[1];

        UsuarioEmpresa::where('id', $usuarioEmpresa_id)
            ->update(['ANULADO' => 1]);

        $usuarios = User::where('anulado', '=', 0)->lists('nombre','id')->toArray();
        $empresas = Empresa::where('ANULADO', '=', 0)->lists('DESCRIPCION', 'ID')->toArray();
        return view('usuariosXempresa.asignacion', compact('usuarios', 'empresas', 'usuario_id'));

        //return redirect::back()->withInput();//return 1;//Redirect::to('empresa/usuario/' . $empresa_id);
    }

    public function anularvendedorSupervisor($id)
    {
        $param = explode('-', $id);
        $supervisionId = $param[0];
        $supervisorId = $param[1];
        $empresa_id = $param[2];

        SupervisorVendedor::where('ID_SUPERVISION', $supervisionId)
            ->update(['ANULADO' => 1]);

        $supervisores = User::join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
            ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
            ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
            ->where('cat_empresa.ID', '=', $empresa_id)
            ->where('users_roles.role_id', '=', 5)
            ->lists('users.nombre', 'users.id')
            ->toArray();

        $vendedores = User::join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
            ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
            ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
            ->where('cat_empresa.ID', '=', $empresa_id)
            ->where('users_roles.role_id', '=', 7)
            ->lists('users.nombre', 'users.id')
            ->toArray();

        return view('equipos.asignacion', compact( 'supervisores', 'vendedores', 'supervisorId', 'empresa_id'));

    }
}

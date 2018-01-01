<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('roles:superAdmin,master');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::where('anulado', '=', 0)->paginate(10);

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function asignaRoleUsuario()
    {
        $usuarios = User::where('anulado', '=', 0)->lists('nombre', 'id')
            ->toArray();

        $roles = Role::lists('name', 'id')
            ->toArray();


        return view('rolesXusuario.asignacion', compact('roles', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function creaRolesUsuario(Request $request)
    {
        if($request->usuario === '' ) {
            Session::flash('info', 'Debe Seleccionar un Usuario!');
            return back()->withInput();
        }
        if(! $request->roles_list) {
            Session::flash('info', '¡Debe seleccionar al menos un role!');
            return back()->withInput();
        } 

        
        $user_id = $request->usuario;
        $user = User::findOrFail($user_id);

        $roles = $request->input('roles_list');

        $user->roles()->sync($roles);

        return redirect::to('roles/' . $user_id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = new Role();
        $role->name = $request->name;

        $role->save();

        return redirect::to('roles');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = $id;

        $roles = Role::lists('name', 'id')
            ->toArray();

        $usuarios = User::lists('nombre', 'id')
            ->toArray();

        $autorizado = Role::join('users_roles', 'users_roles.role_id', '=', 'roles.id')
            ->join('users', 'users.id', '=', 'users_roles.user_id')
            ->where('users.id', '=', $id)
            ->lists('roles.id')
            ->toArray();
        //dd($autorizado);

        $autorizados = []                                              ;
        foreach ($autorizado as $auto) {
            $autorizados[] = intval($auto);
        }
       //dd($autorizados);
        return view('rolesXusuario.asignacion', compact('roles','usuarios', 'autorizados', 'usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return view('roles.edit', compact('role'));
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
        $role = Role::findOrFail($id);

        $role->name = $request->name;

        $role->save();

        return redirect::to('roles');
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
        Role::where('id', $id)
            ->update(['anulado' => 1]);

        return 1; //Redirect::to('presupuestos');
    }
}

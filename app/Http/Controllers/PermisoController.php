<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Permission;
use App\Role;
use App\User;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permisos = Permission::paginate(10);

        return view('permisos.index', compact('permisos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permisos.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function asignaPermisosRole()
    {
        $roles = Role::lists('name', 'id')
                            ->toArray();

        $permisos = Permission::lists('name', 'id')
                                ->toArray();

                              /*  $autorizado = Role::join('roles_permissions', 'roles_permissions.role_id', '=', 'roles.id')
                                                      ->join('permissions', 'permissions.id', '=', 'roles_permissions.permission_id')
                                                      ->where('roles.id', '=', 3)
                                                      ->lists('permissions.id')
                                                      ->toArray();


        $per = []                                              ;
        foreach ($autorizado as $auto) {
          $per[] = intval($auto);
        }*/


        return view('permisosXroles.asignacion', compact('roles', 'permisos', 'usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function asignaPermisosUsuario()
    {
        $usuarios = User::lists('nombre', 'id')
                                ->toArray();

        $permisos = Permission::lists('name', 'id')
                                ->toArray();


        return view('permisosXusuario.asignacion', compact('permisos', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function creaPermisoRole(Request $request)
    {
        $role_id = $request->role;
        $role = Role::findOrFail($role_id);

        $permisos = $request->input('permission_list');
        $role->permissions()->sync($permisos);

        return redirect::to('permisos/' . $role_id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function creaPermisoUsuario(Request $request)
    {
        $user_id = $request->usuario;
        $user = User::findOrFail($user_id);

        $permisos = $request->input('list_permission');
        $user->permissions()->sync($permisos);

        return redirect::to('permisos/usuario/' . $user_id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $permiso = new Permission();
        $permiso->name = $request->name;

        $permiso->save();

        return redirect::to('permisos');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = $id;
        $roles = Role::lists('name', 'id')
                            ->toArray();

        $permisos = Permission::lists('name', 'id')
                                ->toArray();

        $usuarios = User::lists('nombre', 'id')
                                ->toArray();

        $autorizado = Role::join('roles_permissions', 'roles_permissions.role_id', '=', 'roles.id')
                              ->join('permissions', 'permissions.id', '=', 'roles_permissions.permission_id')
                              ->where('roles.id', '=', $id)
                              ->lists('permissions.id')
                              ->toArray();


        $autorizados = []                                              ;
        foreach ($autorizado as $auto) {
            $autorizados[] = intval($auto);
        }
        //dd($autorizados);
        return view('permisosXroles.asignacion', compact('roles','permisos', 'autorizados', 'role'));

    }

    public function showPermisos($id)
    {
        $usuario = $id;

        $permisos = Permission::lists('name', 'id')
                                ->toArray();

        $usuarios = User::lists('nombre', 'id')
                                ->toArray();

        $autorizado = User::join('users_permissions', 'users_permissions.user_id', '=', 'users.id')
                              ->join('permissions', 'permissions.id', '=', 'users_permissions.permission_id')
                              ->where('users.id', '=', $id)
                              ->lists('permissions.id')
                              ->toArray();

//dd($autorizado);
        $autorizados = []                                              ;
        foreach ($autorizado as $auto) {
            $autorizados[] = intval($auto);
        }
        //dd($autorizados);
        return view('permisosXusuario.asignacion', compact('usuarios','permisos', 'autorizados', 'usuario'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permiso = Permission::findOrFail($id);

        return view('permisos.edit', compact('permiso'));
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
        $permiso = Permission::findOrFail($id);

        $permiso->name = $request->name;

        $permiso->save();

        return redirect::to('permisos');
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
}

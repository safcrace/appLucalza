<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App\Empresa;
use App\UsuarioEmpresa;

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

        return view('usuarios.index', compact('users', 'empresa'));
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
    public function store(Request $request)
    {
        $empresa_id = $request->EMPRESA_ID;
        //dd($empresa_id);

        $usuario = new User();

        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->usuario = $request->usuario;
        $usuario->tel_codpais = $request->tel_codpais;
        $usuario->telefono = $request->telefono;
        $usuario->activo = $request->activo;
        $usuario->anulado = $request->anulado;

        if ($usuario->anulado === null) {
            $usuario->anulado = 0;
        }

        $usuario->save();

        UsuarioEmpresa::insert( ['USER_ID' => $usuario->id, 'EMPRESA_ID' => $empresa_id, 'ANULADO' => 0] );

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
        $usuario = User::findOrFail($id);
        //$moneda->fill($request->all());    bcrypt($request->PASSSAP)

        if ($request->anulado === null) {
            $request->anulado = 0;
        }

        User::where('ID', $id)
                ->update(['nombre' => $request->nombre, 'email' => $request->email, 'usuario' => $request->usuario, 'tel_codpais' => $request->tel_codpais,
                          'telefono' => $request->telefono, 'activo' => $request->activo, 'anulado' => $request->anulado]);

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
        User::where('ID', $id)
                ->update(['ANULADO' => 1]);

        return Redirect::to('empresas');
    }
}

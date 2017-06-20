<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Ruta;
use App\Empresa;
use App\UsuarioEmpresa;
use App\UsuarioRuta;

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
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
      */
      public function indexRutasUsuario($id)
      {
          $param = explode('-', $id);
          $empresa_id = $param[0];
          $usuario_id = $param[1];

          $rutas = Ruta::select('cat_ruta.ID', 'cat_ruta.CLAVE', 'cat_ruta.DESCRIPCION', 'users.nombre')
                              ->join('cat_usuarioruta', 'cat_usuarioruta.RUTA_ID', '=', 'cat_ruta.ID')
                              ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                              ->where('users.id', '=', $param[1])
                              ->where('cat_ruta.EMPRESA_ID', '=', $param[0])
                              ->where('cat_ruta.ANULADO', '=', 0)
                              ->paginate(10);
          //dd($rutas);

          return view('rutas.indexRutasUsuario', compact('rutas', 'empresa_id', 'usuario_id'));
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
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function empresaCreateRutaUsuario($id)
     {
         $param = explode('-', $id);
         $empresa_id = $param[0];
         $usuario_id = $param[1];

         $rutas = Ruta::where('EMPRESA_ID', '=', $empresa_id)
                             ->where('ANULADO', '=', 0)
                             ->lists('DESCRIPCION', 'ID')
                             ->toArray();

         return view('rutas.createUsuarioRuta', compact('rutas','usuario_id'));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUsuarioRuta(Request $request)
    {
        $usuarioRuta = new UsuarioRuta();



        UsuarioRuta::insert( ['USER_ID' => $request->USUARIO_ID, 'RUTA_ID' => $request->RUTA_ID, 'ANULADO' => 0] );

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function UsuarioRutaEdit($id)
    {
        $param = explode('-', $id);
        $empresa_id = $param[0];
        $usuario_id = $param[1];
        $ruta_id = $param[2];

        $usuarioRuta = UsuarioRuta::select('*')
                                    ->where('USER_ID', '=', $usuario_id)
                                    ->where('RUTA_ID', '=', $ruta_id)
                                    ->first();



        $rutas = Ruta::where('EMPRESA_ID', '=', $empresa_id)
                            ->where('ANULADO', '=', 0)
                            ->lists('DESCRIPCION', 'ID')
                            ->toArray();



        return view('rutas.editUsuarioRuta', compact('usuarioRuta', 'rutas', 'usuario_id'));
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUsuarioRuta(Request $request, $id)
    {
        $usuario_id = $request->USUARIO_ID;
        $ruta_id = $id;
        //echo ($id);

        //dd($request->all());

        if ($request->ANULADO === null) {
            $request->ANULADO = 0;
        }

        UsuarioRuta::where('USER_ID', '=', $usuario_id)
                ->where('RUTA_ID', '=', $ruta_id)
                ->update(['USER_ID' => $request->USUARIO_ID, 'RUTA_ID' => $request->RUTA_ID, 'ANULADO' => $request->ANULADO]);

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

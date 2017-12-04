<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRutaRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Ruta;
use App\User;
use App\Empresa;
use App\UsuarioEmpresa;
use App\UsuarioRuta;
use Illuminate\Support\Facades\Session;

class RutaController extends Controller
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
     public function indexRuta($id)
     {
         $param = explode('-', $id);
         $empresa_id = $param[0];
         $descripcion = $param[1];

         $rutas = Ruta::select('*')
                             ->where('cat_ruta.EMPRESA_ID', '=', $empresa_id)
                             ->where('cat_ruta.TIPO_GASTO', '=', $descripcion)
                             ->where('cat_ruta.ANULADO', '=', 0)
                             ->paginate(10);
         $empresa_id = $empresa_id;
         $nombreEmpresa = Empresa::select('DESCRIPCION')->where('ID', '=', $empresa_id)->first();

         return view('rutas.index', compact('rutas', 'empresa_id', 'nombreEmpresa', 'descripcion'));
     }

     /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
      */
      public function indexRutasUsuario($id)
      {
          $param = explode('-', $id);
          $usuario_id = $param[0];
          $descripcion = $param[1];

          $empresa_id = Session::get('empresa');

          $user =  User::select('nombre')->where('id', '=', $usuario_id)->first();

          $rutas = Ruta::select('cat_ruta.ID', 'cat_ruta.CLAVE', 'cat_ruta.DESCRIPCION', 'users.nombre')
                              ->join('cat_usuarioruta', 'cat_usuarioruta.RUTA_ID', '=', 'cat_ruta.ID')
                              ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                              ->where('users.id', '=', $usuario_id)
                              ->where('cat_ruta.EMPRESA_ID', '=', $empresa_id)
                              ->where('cat_ruta.TIPO_GASTO', '=', $descripcion)
                              ->where('cat_usuarioruta.ANULADO', '=', 0)
                              ->paginate(10);

          $usuario = User::select('nombre')
                                ->join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
                                ->where('cat_usuarioempresa.EMPRESA_ID', '=', $empresa_id)
                                //->where('RUTA_ID', '=', $ruta_id)
                                ->first();
          //dd($usuario->nombre);

          return view('rutas.indexRutasUsuario', compact('rutas', 'empresa_id', 'usuario_id', 'user', 'descripcion'));
      }

     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function empresaCreateRuta($id)
     {
         $param = explode('-', $id);
         $empresa_id = $param[0];
         $descripcion = $param[1];

         return view('rutas.create', compact('empresa_id', 'descripcion'));
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
         $descripcion = $param[2];
//dd($descripcion);

         $seleccionadas = Ruta::join('cat_usuarioruta', 'cat_usuarioruta.RUTA_ID', '=', 'cat_ruta.ID')
                             ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                             ->where('users.id', '=', $usuario_id)
                             ->where('cat_ruta.EMPRESA_ID', '=', $empresa_id)
                             ->where('cat_usuarioruta.ANULADO', '=', 0)
                             ->lists('cat_ruta.ID')
                             ->toArray();
//dd($seleccionadas);

         $rutas = Ruta::join('cat_empresa', 'cat_empresa.ID', '=', 'cat_ruta.EMPRESA_ID')
                             ->where('cat_empresa.ID', '=', $empresa_id)
                             ->where('cat_ruta.ANULADO', '=', 0)
                             ->where('cat_ruta.TIPO_GASTO', '=', $descripcion)
                             ->whereNotIn('cat_ruta.ID', $seleccionadas)
                             ->lists('cat_ruta.DESCRIPCION', 'cat_ruta.ID')
                             ->toArray();

//
         return view('rutas.createUsuarioRuta', compact('rutas','usuario_id', 'empresa_id', 'descripcion'));
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
    public function store(CreateRutaRequest $request)
    {
        $empresa_id = $request->EMPRESA_ID;

        $existeCodigo = Ruta::select('ID')->where('CLAVE', '=', $request->CLAVE)->first();

        if ($existeCodigo) {
            return back()->withInput()->with('info', 'El Código ya existe en la Base de Datos.');
        }

        $ruta = new Ruta();

        $ruta->EMPRESA_ID = $empresa_id;
        $ruta->CLAVE = $request->CLAVE;
        $ruta->TIPO_GASTO = $request->TIPO_GASTO;
        $ruta->DESCRIPCION = $request->DESCRIPCION;
        $ruta->ANULADO = $request->ANULADO;

        if ($ruta->ANULADO === null) {
            $ruta->ANULADO = 0;
        }

        $ruta->save();

        return redirect::to('empresa/ruta/' . $empresa_id . '-' . $ruta->TIPO_GASTO);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUsuarioRuta(Request $request, $id)
    {
        $usuarioRuta = new UsuarioRuta();

        $param = explode('-', $id);
        $empresa_id = $param[0];
        $usuario_id = $param[1];
        $ruta_id = $request->RUTA_ID;

        //dd('esta es la empresa: ' . $empresa_id . ' este es el usuario: ' . $usuario_id . ' y esta es la ruta: ' . $ruta_id);

        $existe = UsuarioRuta::select('ID')
                                    ->where('USER_ID', '=', $usuario_id)
                                    ->where('cat_usuarioruta.RUTA_ID', '=', $ruta_id)
                                    ->first();

        if ($existe === null) {
            $usuarioRuta->USER_ID = $request->USUARIO_ID;
            $usuarioRuta->RUTA_ID = $request->RUTA_ID;
            $usuarioRuta->ANULADO = 0;
            $usuarioRuta->save();
        } else {
            UsuarioRuta::where('ID', '=', $existe->ID)
                                ->update(['ANULADO' => 0]);
        }


        return redirect('rutas/usuario/' . $usuario_id . '-' . $request->TIPO_GASTO);
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
        $ruta_id = $param[0];
        $descripcion = $param[1];

        $ruta = Ruta::findOrFail($ruta_id);

        return view('rutas.edit', compact('ruta', 'descripcion'));
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
        $descripcion = $param[3];

        $usuarioRuta = UsuarioRuta::select('*')
                                    ->where('USER_ID', '=', $usuario_id)
                                    ->where('RUTA_ID', '=', $ruta_id)
                                    ->first();
        //dd($usuarioRuta);
        $seleccionadas = Ruta::join('cat_usuarioruta', 'cat_usuarioruta.RUTA_ID', '=', 'cat_ruta.ID')
                                    ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                    ->where('users.id', '=', $usuario_id)
                                    ->where('cat_ruta.EMPRESA_ID', '=', $empresa_id)
                                    ->where('cat_usuarioruta.ANULADO', '=', 0)
                                    ->lists('cat_ruta.ID')
                                    ->toArray();

//dd($seleccionadas);
        $rutaActual = $usuarioRuta->RUTA_ID;
        $claveActual = array_filter($seleccionadas, function($v) use ($rutaActual) {
            //dd($rutaActual);
            return $v == $rutaActual;
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($claveActual as $clave=>$valor) {
           $valorSeleccionado = $valor;
        }

        $valorSeleccionado = Ruta::select('DESCRIPCION')->where('ID', '=', $valorSeleccionado)->first();

        //dd($valorSeleccionado->DESCRIPCION);
        $rutas = Ruta::join('cat_usuarioruta', 'cat_usuarioruta.RUTA_ID', '=', 'cat_ruta.ID')
                                    ->where('cat_ruta.EMPRESA_ID', '=', $empresa_id)
                                    ->where('cat_ruta.ANULADO', '=', 0)
                                    ->where('cat_ruta.TIPO_GASTO', '=', $descripcion)
                                    ->whereNotIn('cat_ruta.ID', $seleccionadas)
                                    ->lists('cat_ruta.DESCRIPCION', 'cat_ruta.ID')
                                    ->toArray();

        //array_push($rutas, $valorSeleccionado->DESCRIPCION);
        //dd($rutas);
        //$combo_id = count($rutas) - 1;
        //dd($combo_id);


        return view('rutas.editUsuarioRuta', compact('usuarioRuta', 'rutas', 'usuario_id', 'empresa_id', 'descripcion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateRutaRequest $request, $id)
    {
        $ruta = Ruta::findOrFail($id);
        $empresa_id = $request->EMPRESA_ID;
        //$moneda->fill($request->all());

        if ($request->ANULADO === null) {
            $request->ANULADO = 0;
        }

        Ruta::where('ID', $ruta->ID)
                ->update(['CLAVE' => $request->CLAVE, 'DESCRIPCION' => $request->DESCRIPCION, 'ANULADO' => $request->ANULADO]);

        return redirect::to('empresa/ruta/' . $empresa_id . '-' . $ruta->TIPO_GASTO);
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
        //$usuario_id = $request->USUARIO_ID;
        //$ruta_id = $id;

        $param = explode('-', $id);
        $usuarioRuta_id = $param[1];
        $usuario_id = $param[2];
        $ruta_id = $param[0];
        //echo ($id);

        //dd($request->all());

        if ($request->ANULADO === null) {
            $request->ANULADO = 0;
        }

        UsuarioRuta::where('ID', '=', $usuarioRuta_id)
                        ->update(['ANULADO' => 1]);


        $existe = UsuarioRuta::select('ID')
                                    ->where('USER_ID', '=', $usuario_id)
                                    ->where('RUTA_ID', '=', $request->RUTA_ID)
                                    ->first();

        if ($existe === null) {
            //dd('entro');
            $usuarioRuta->USER_ID = $request->USUARIO_ID;
            $usuarioRuta->RUTA_ID = $request->RUTA_ID;
            $usuarioRuta->ANULADO = 0;
            $usuarioRuta->save();
        } else {
            UsuarioRuta::where('ID', '=', $existe->ID)
                ->update(['ANULADO' => 0]);
        }

        return redirect('rutas/usuario/' . $usuario_id);
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rutasPresupuestadas($id)
    {
        $param = explode('&', $id);
        $fechaInicio = $param[0];
        $fechaFinal = $param[1];
        $tipoLiquidacion = $param[2];
        $usuario_id = $param[3];

        $rutas = Ruta::join('cat_usuarioruta', 'cat_usuarioruta.RUTA_ID', '=', 'cat_ruta.ID')
                            ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                            ->join('pre_presupuesto', 'pre_presupuesto.USUARIORUTA_ID', '=', 'cat_usuarioruta.ID')
                            ->where('cat_ruta.TIPO_GASTO', '=', $tipoLiquidacion)
                            ->where('cat_usuarioruta.USER_ID', '=', $usuario_id)
                            ->where('pre_presupuesto.ANULADO', '=', 0)
                            ->where('pre_presupuesto.VIGENCIA_INICIO', '<=', $fechaInicio)
                            ->where('pre_presupuesto.VIGENCIA_FINAL', '>=', $fechaFinal)
                            ->select('cat_ruta.ID', 'cat_ruta.DESCRIPCION')
                            ->get()
                            ->toArray();
        if ($rutas) {
            array_unshift($rutas, ['ID' => '', 'DESCRIPCION' => 'Seleccione una Opción']);
        } else {
            array_unshift($rutas, ['ID' => '', 'DESCRIPCION' => 'No existen rutas con Presupuesto']);
        }        

        return $rutas;

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

        /* Verifica si Ruta posee Presupuesto Activo */
        $fechaActual = \Carbon\Carbon::now();

        $presupuestoActivo = DB::table('cat_ruta')
                                    ->join('cat_usuarioruta', 'cat_usuarioruta.RUTA_ID', '=', 'cat_ruta.ID')
                                    ->join('pre_presupuesto', 'pre_presupuesto.USUARIORUTA_ID', '=', 'cat_usuarioruta.ID')
                                    ->where('pre_presupuesto.VIGENCIA_FINAL', '>', $fechaActual)
                                    ->where('cat_ruta.ID', '=', $id)
                                    ->count();

        if($presupuestoActivo == 0) {
            Ruta::where('ID', $id)
                ->update(['ANULADO' => 1]);

            return 1; //Redirect::to('empresa/ruta/' . $empresa_id);
        } else {
            return 0; //Redirect::to('empresa/ruta/' . $empresa_id);
        }



    }

    /**
     * Anule the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function anularRutaUsuario($id)
    {
        $param = explode('-', $id);
        $id = $param[0];
        $usuario_id = $param[1];
        $empresa_id = $param[2];
        UsuarioRuta::where('USER_ID', $usuario_id)
                            ->where('RUTA_ID', $id)
                            ->update(['ANULADO' => 1]);

        return 1; // Redirect::to('rutas/usuario/' . $empresa_id . '-' . $usuario_id);
    }
}

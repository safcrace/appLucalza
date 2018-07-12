<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePresupuestoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App\Empresa;
use App\Ruta;
use App\Presupuesto;
use App\UsuarioRuta;
use App\DetallePresupuesto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class PresupuestoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('roles:superAdmin,administrador');
        Carbon::setLocale('es');
    }

    /**
 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */
    public function index()
    {       
        $empresa_id = Session::get('empresa');
        $tipoGasto = 'Rutas';

        $presupuestos = Presupuesto::select('pre_presupuesto.ID as ID', 'users.nombre as USUARIO', 'cat_ruta.DESCRIPCION as RUTA', 'pre_presupuesto.VIGENCIA_INICIO', 'pre_presupuesto.VIGENCIA_FINAL', 'pre_presupuesto.ANULADO')
            ->orderBy('pre_presupuesto.ID')
            ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'pre_presupuesto.USUARIORUTA_ID')
            ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
            ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
            ->join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id' )
            ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')            
            ->where('cat_empresa.ID', '=', $empresa_id)
            ->where('cat_ruta.TIPO_GASTO', '=', $tipoGasto)
            ->paginate(10);
        
        return view('presupuestos.index', compact( 'presupuestos', 'tipoGasto'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexOtrosGastos()
    {        
        //$usuario_id = Auth::user()->id;
        //dd($usuario_id);
        $empresa_id = Session::get('empresa');
        $tipoGasto = 'Otros Gastos';
        $presupuestos = Presupuesto::select('pre_presupuesto.ID as ID', 'users.nombre as USUARIO', 'cat_ruta.DESCRIPCION as RUTA', 'pre_presupuesto.VIGENCIA_INICIO', 'pre_presupuesto.VIGENCIA_FINAL', 'pre_presupuesto.ANULADO')
            ->orderBy('pre_presupuesto.ID')
            ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'pre_presupuesto.USUARIORUTA_ID')
            ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
            ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
            ->join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id' )
            ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')            
            ->where('cat_empresa.ID', '=', $empresa_id)
            ->where('cat_ruta.TIPO_GASTO', '=', $tipoGasto)
            ->paginate(10);
        

        return view('presupuestos.index', compact( 'presupuestos', 'tipoGasto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function presupuestoCreate($id)
    {  
        $tipoGasto = $id;
        
        $empresa_id = Session::get('empresa');

        $moneda = Empresa::select('cat_moneda.ID', 'cat_moneda.DESCRIPCION')
            ->join('cat_moneda', 'cat_moneda.ID', '=', 'cat_empresa.MONEDA_ID')
            ->where('cat_empresa.ID', '=',  $empresa_id)
            ->first();

        $usuarios = User::join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
            ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
            ->join('cat_usuarioruta', 'cat_usuarioruta.USER_ID', '=', 'users.id')
            ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
            ->where('cat_usuarioempresa.EMPRESA_ID', '=', $empresa_id)
            ->where('cat_ruta.TIPO_GASTO', '=', $tipoGasto)
            ->where('users.anulado', '=', 0)
            ->lists('users.nombre', 'users.id')
            ->toArray();

            

        $rutas = Ruta::join('cat_usuarioruta', 'cat_usuarioruta.RUTA_ID', '=', 'cat_ruta.ID')
            ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
            ->where('cat_usuarioruta.ANULADO', '=', 0)            
            ->where('cat_ruta.TIPO_GASTO', '=', $tipoGasto)
            //->where('cat_ruta.ANULADO', '=', 0)
            ->lists('cat_ruta.DESCRIPCION', 'cat_ruta.ID')
            ->toArray();
            
        $monedaEmpresa = Empresa::select('MONEDA_LOCAL','MONEDA_SYS')->where('ID', '=', $empresa_id)->first();

    
        $vigenciaInicio = null;
        $vigenciaFinal = null;

        if ($tipoGasto == 'Rutas') {
            $rutaPresupuesto = 'presupuestos.index';
        } else {
            $rutaPresupuesto = 'indexPresupuestoOtrosGastos';
        }


        return view('presupuestos.create', compact('usuarios', 'rutas', 'monedaEmpresa', 'vigenciaInicio', 'vigenciaFinal', 'tipoGasto', 'rutaPresupuesto'));
    
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $empresa_id = Session::get('empresa');

        $moneda = Empresa::select('cat_moneda.ID', 'cat_moneda.DESCRIPCION')
            ->join('cat_moneda', 'cat_moneda.ID', '=', 'cat_empresa.MONEDA_ID')
            ->where('cat_empresa.ID', '=',  $empresa_id)
            ->first();

        $usuarios = User::join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
            ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
            ->where('cat_usuarioempresa.EMPRESA_ID', '=', $empresa_id)
            ->where('users.activo', '=', '1')
            ->lists('users.nombre', 'users.id')
            ->toArray();

        $rutas = Ruta::join('cat_usuarioruta', 'cat_usuarioruta.RUTA_ID', '=', 'cat_ruta.ID')
            ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
            ->lists('cat_ruta.DESCRIPCION', 'cat_ruta.ID')
            ->toArray();
        
        $vigenciaInicio = null;
        $vigenciaFinal = null;

        return view('presupuestos.create', compact('usuarios', 'rutas', 'moneda', 'vigenciaInicio', 'vigenciaFinal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePresupuestoRequest $request)
    {
        /** Se determina el nombre de la ruta */
        $nombreRuta = UsuarioRuta::join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                    ->where('cat_usuarioruta.USER_ID', '=', $request->USUARIO_ID)
                                    ->where('cat_usuarioruta.RUTA_ID', '=', $request->RUTA_ID)
                                    ->select('cat_ruta.DESCRIPCION')->first();

        if ($request->TIPO_GASTO == 'Rutas') {
            $texto = ' de esta Ruta';
        } else {
            $texto = ' de este Gasto';
            if ($nombreRuta == (strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACIóN') || strtoupper($nombreRuta->DESCRIPCION) == 'DEPRECIACION') {
                if ( $request->ASIGNACION_MENSUAL <= 0) {
                    return back()->withInput()->with('info', 'Debe ingresar una Asignación Valida');
                }            
            }
        }

        $usuarioRuta_id = UsuarioRuta::select('ID')
                        ->where('USER_ID', '=', $request->USUARIO_ID)
                        ->where('RUTA_ID', '=', $request->RUTA_ID)
                        ->first();

        $presupuestosRuta = Presupuesto::select('ID', 'VIGENCIA_INICIO', 'VIGENCIA_FINAL')
                                                ->where('USUARIORUTA_ID', '=', $usuarioRuta_id->ID)
                                                ->where('ANULADO', '=', 0)
                                                ->get();

        //dd($request->all());

        if ($request->VIGENCIA_INICIO >= $request->VIGENCIA_FINAL) {
            return back()->withInput()->with('info', 'La Fecha de Inicio no puede ser Mayor a la Fecha Final');
        }
        
        foreach ($presupuestosRuta as $presupuestoRuta) {
            if ($request->VIGENCIA_INICIO <= $presupuestoRuta->VIGENCIA_FINAL) {
                return back()->withInput()->with('info', 'Por Favor Revise el Rango de Fecha, ya que se Traslapa con otro presupuesto' . $texto);
            }
        }       

        //dd('pasamos porue no existe condicion!' . $presupuestosRuta);

        $presupuesto = new Presupuesto();

        $presupuesto->USUARIORUTA_ID = $usuarioRuta_id->ID;
        $presupuesto->MONEDA_ID = $request->MONEDA_ID;

        $presupuesto->VIGENCIA_INICIO = $request->VIGENCIA_INICIO;
        $presupuesto->VIGENCIA_FINAL = $request->VIGENCIA_FINAL;
        $presupuesto->ASIGNACION_MENSUAL = $request->ASIGNACION_MENSUAL;
        $presupuesto->ANULADO = $request->ANULADO;

        $presupuesto->save();

        if ($presupuesto->ASIGNACION_MENSUAL > 0 ) {
            
            return redirect::to('presupuesto/otrosgastos');
        } else {
            return redirect::to('presupuestos/' . $presupuesto->id . '-' . $request->TIPO_GASTO . '/edit')->withInput();
        }      
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $param = explode('-', $id);
        $usuario_id = $param[0];
        $tipoGasto = $param[1];


        $empresa_id = Session::get('empresa');

        $usuarios = User::join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
        ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
        ->join('cat_usuarioruta', 'cat_usuarioruta.USER_ID', '=', 'users.id')
        ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
        ->where('cat_usuarioempresa.EMPRESA_ID', '=', $empresa_id)
        ->where('cat_ruta.TIPO_GASTO', '=', $tipoGasto)
        ->where('users.anulado', '=', 0)
        ->lists('users.nombre', 'users.id')
        ->toArray();

        //dd($empresa_id);
        $rutas = User::join('cat_usuarioruta', 'cat_usuarioruta.USER_ID', '=', 'users.id')
            ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
            ->where('cat_usuarioruta.USER_ID', '=', $usuario_id)
            ->where('cat_ruta.TIPO_GASTO', '=', $tipoGasto)
            ->lists('cat_ruta.DESCRIPCION', 'cat_ruta.ID')
            ->toArray();
           

        $moneda = Empresa::select('cat_moneda.ID', 'cat_moneda.DESCRIPCION')
            ->join('cat_moneda', 'cat_moneda.ID', '=', 'cat_empresa.MONEDA_ID')
            ->where('cat_empresa.ID', '=',  $empresa_id)
            ->first();

        $monedaEmpresa = Empresa::select('MONEDA_LOCAL','MONEDA_SYS')->where('ID', '=', $empresa_id)->first();

        $vigenciaInicio = null;
        $vigenciaFinal = null;

        if ($tipoGasto == 'Rutas') {
            $rutaPresupuesto = 'presupuestos.index';
        } else {
            $rutaPresupuesto = 'indexPresupuestoOtrosGastos';
        }

        return view('presupuestos.create', compact('usuario_id', 'usuarios', 'rutas', 'monedaEmpresa', 'vigenciaInicio', 'vigenciaFinal', 'tipoGasto', 'rutaPresupuesto'));
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
        $presupuesto_id = $param[0];
        $tipoGasto = $param[1];

        $presupuesto = Presupuesto::findOrFail($presupuesto_id);

        //$usuario_id = Auth::user()->id;        

        $empresa_id = Session::get('empresa');

        $moneda = Empresa::select('cat_moneda.ID', 'cat_moneda.DESCRIPCION')
            ->join('cat_moneda', 'cat_moneda.ID', '=', 'cat_empresa.MONEDA_ID')
            ->where('cat_empresa.ID', '=',  $empresa_id)
            ->first();

        $monedaEmpresa = Empresa::select('MONEDA_LOCAL','MONEDA_SYS')->where('ID', '=', $empresa_id)->first();


            $usuarios = User::join('cat_usuarioempresa', 'cat_usuarioempresa.USER_ID', '=', 'users.id')
            ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_usuarioempresa.EMPRESA_ID')
            ->join('cat_usuarioruta', 'cat_usuarioruta.USER_ID', '=', 'users.id')
            ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
            ->where('cat_usuarioempresa.EMPRESA_ID', '=', $empresa_id)
            ->where('cat_ruta.TIPO_GASTO', '=', $tipoGasto)
            ->where('users.anulado', '=', 0)
            ->lists('users.nombre', 'users.id')
            ->toArray();

        /*$frecuencia = FrecuenciaTiempo::lists('DESCRIPCION', 'ID')
                                        ->toArray();*/

        $combos = Presupuesto::select('pre_presupuesto.ID as ID', 'users.id as USUARIO', 'cat_ruta.ID as RUTA', 'cat_empresa.ID as EMPRESA')
                                      ->join('cat_usuarioruta', 'cat_usuarioruta.ID', '=', 'pre_presupuesto.USUARIORUTA_ID')
                                      ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
                                      ->join('cat_ruta', 'cat_ruta.ID', '=', 'cat_usuarioruta.RUTA_ID')
                                      ->join('cat_empresa', 'cat_empresa.ID', '=', 'cat_ruta.EMPRESA_ID')
                                      ->where('pre_presupuesto.ID', '=', $presupuesto_id)
                                      ->first();

        $usuario_id = $combos->USUARIO;

        $ruta_id = $combos->RUTA;
        
        $vigenciaInicio = $presupuesto->VIGENCIA_INICIO;
        $vigenciaFinal = $presupuesto->VIGENCIA_FINAL;
        
            $rutas = Ruta::join('cat_usuarioruta', 'cat_usuarioruta.RUTA_ID', '=', 'cat_ruta.ID')
            ->join('users', 'users.id', '=', 'cat_usuarioruta.USER_ID')
            ->where('cat_usuarioruta.USER_ID', '=', $usuario_id)
            ->where('cat_usuarioruta.ANULADO', '=', 0)            
            ->where('cat_ruta.TIPO_GASTO', '=', $tipoGasto)
            ->where('cat_ruta.ANULADO', '=', 0)
            ->lists('cat_ruta.DESCRIPCION', 'cat_ruta.ID')
            ->toArray();
        
        $detallePresupuestos = DetallePresupuesto::select('pre_detpresupuesto.ID', 'cat_tipogasto.DESCRIPCION as TIPOGASTO', 'pre_detpresupuesto.MONTO', 'pre_detpresupuesto.TIPOASIGNACION_ID', 'cat_frecuenciatiempo.DESCRIPCION as FRECUENCIA', 'pre_detpresupuesto.ANULADO')
                                                  ->join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'pre_detpresupuesto.TIPOGASTO_ID')
                                                  ->join('cat_frecuenciatiempo', 'cat_frecuenciatiempo.ID', '=', 'pre_detpresupuesto.FRECUENCIATIEMPO_ID')
                                                  ->where('pre_detpresupuesto.PRESUPUESTO_ID', '=', $presupuesto_id)
                                                  //->where('pre_detpresupuesto.ANULADO', '=', 0)
                                                  ->paginate(10);

        if ($tipoGasto == 'Rutas') {
            $rutaPresupuesto = 'presupuestos.index';
        } else {
            $rutaPresupuesto = 'indexPresupuestoOtrosGastos';
        }

        

        return view('presupuestos.edit', compact('presupuesto', 'usuarios', 'rutas', 'usuario_id', 'ruta_id', 'detallePresupuestos', 'monedaEmpresa', 'vigenciaInicio', 'vigenciaFinal', 'tipoGasto', 'rutaPresupuesto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreatePresupuestoRequest $request, $id)
    {   
        $usuarioRuta_id = UsuarioRuta::select('ID')
                        ->where('USER_ID', '=', $request->USUARIO_ID)
                        ->where('RUTA_ID', '=', $request->RUTA_ID)
                        ->first();

        //$proveedor = Proveedor::findOrFail($id);
        //$moneda->fill($request->all());




        Presupuesto::where('ID', $id)
                ->update(['USUARIORUTA_ID' => $usuarioRuta_id->ID, 'MONEDA_ID' => $request->MONEDA_ID, 'VIGENCIA_INICIO' => $request->VIGENCIA_INICIO,
                          'VIGENCIA_FINAL' => $request->VIGENCIA_FINAL, 'ASIGNACION_MENSUAL' =>  $request->ASIGNACION_MENSUAL, 'ANULADO' => $request->ANULADO]);

        if ($request->TIPO_GASTO == 'Rutas') {            
            $rutaPresupuesto = 'presupuestos';
        } else {
            $rutaPresupuesto = 'presupuesto/otrosgastos';            
        }

        return Redirect::to($rutaPresupuesto);
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
        $anulado = Presupuesto::where('id', '=', $id)->pluck('anulado');
       
            if ($anulado == 1) {
                Presupuesto::where('id', $id)
                            ->update(['ANULADO' => 0]);
                $anular = 'No';
            } else {
                Presupuesto::where('id', $id)
                ->update(['ANULADO' => 1]);            
                $anular = 'Si';
            }  
            return $anular;           
    }

}

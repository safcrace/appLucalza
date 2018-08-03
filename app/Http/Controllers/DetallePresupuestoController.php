<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDetallePresupuestoRequest;
use App\SubcategoriaTipoGasto;
use App\TipoAsignacion;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\FrecuenciaTiempo;
use App\TipoGasto;
use App\DetallePresupuesto;
use Illuminate\Support\Facades\Session;

class DetallePresupuestoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('roles:superAdmin,administrador');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function presupuestoCreateDetalle($id)
     {  
         $param = explode('-', $id);
         $presupuesto_id = $param[0];
         $tipo = $param[1];


         $empresa_id = Session::get('empresa');

         $frecuencia = FrecuenciaTiempo::where('ANULADO', 0)->lists('DESCRIPCION', 'ID')
                                         ->toArray();

         $tipoGasto = TipoGasto::where('EMPRESA_ID', '=', $empresa_id)
                                        ->where('ANULADO', '=', 0)
                                        ->lists('DESCRIPCION', 'ID')                                        
                                        ->toArray();

         /* $subTipoGasto = SubcategoriaTipoGasto::join('cat_tipogasto', 'cat_tipogasto.ID', '=', 'cat_subcategoria_tipogasto.TIPOGASTO_ID')
                ->where('cat_tipogasto.EMPRESA_ID', '=', $empresa_id)
                ->lists('cat_subcategoria_tipogasto.DESCRIPCION', 'cat_subcategoria_tipogasto.TIPOGASTO_ID')
                ->toArray(); */
            

         $tipoAsignacion = TipoAsignacion::lists('DESCRIPCION', 'ID')
             ->toArray();

         if ($tipo == 'Rutas') {
             $rutaPresupuesto = 'presupuestos.edit';
         } else {
             $rutaPresupuesto = 'presupuestos.edit';
         }
         $detallePresupuesto = new DetallePresupuesto();
         $detallePresupuesto->PROYECTO = NULL;
         $detallePresupuesto->DESCPROYECTO = 'Presione el Icono de Carga';
         $detallePresupuesto->CENTROCOSTO1 = NULL;
         $detallePresupuesto->DESCCENTRO1 = 'Presione el Icono de Carga';
         $detallePresupuesto->CENTROCOSTO2 = NULL;
         $detallePresupuesto->DESCCENTRO2 = 'Presione el Icono de Carga';
         $detallePresupuesto->CENTROCOSTO3 = NULL;
         $detallePresupuesto->DESCCENTRO3 = 'Presione el Icono de Carga';
         $detallePresupuesto->CENTROCOSTO4 = NULL;
         $detallePresupuesto->DESCCENTRO4 = 'Presione el Icono de Carga';
         $detallePresupuesto->CENTROCOSTO5 = NULL;
         $detallePresupuesto->DESCCENTRO5 = 'Presione el Icono de Carga';
         
         return view('detallePresupuestos.create', compact('presupuesto_id', 'tipoGasto', 'frecuencia', 'tipoAsignacion', 'tipo', 'rutaPresupuesto', 'detallePresupuesto'));
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDetallePresupuestoRequest $request)
    {       
        $detallePresupuesto = new DetallePresupuesto();
        $detallePresupuesto->PRESUPUESTO_ID = $request->PRESUPUESTO_ID;
        $detallePresupuesto->TIPOGASTO_ID = $request->TIPOGASTO_ID;
        $detallePresupuesto->TIPOASIGNACION_ID = $request->TIPOASIGNACION_ID;
        $detallePresupuesto->MONTO = $request->MONTO;
        $detallePresupuesto->FRECUENCIATIEMPO_ID = $request->FRECUENCIATIEMPO_ID;
        $detallePresupuesto->PROYECTO = $request->PROYECTO;
        $detallePresupuesto->DESCPROYECTO = $request->DESCPROYECTO;
        $detallePresupuesto->CENTROCOSTO1 = $request->CENTROCOSTO1;
        $detallePresupuesto->CENTROCOSTO2 = $request->CENTROCOSTO2;
        $detallePresupuesto->CENTROCOSTO3 = $request->CENTROCOSTO3;
        $detallePresupuesto->CENTROCOSTO4 = $request->CENTROCOSTO4;
        $detallePresupuesto->CENTROCOSTO5 = $request->CENTROCOSTO5;
        $detallePresupuesto->DESCCENTRO1 = $request->DESCCENTRO1;
        $detallePresupuesto->DESCCENTRO2 = $request->DESCCENTRO2;
        $detallePresupuesto->DESCCENTRO3 = $request->DESCCENTRO3;
        $detallePresupuesto->DESCCENTRO4 = $request->DESCCENTRO4;
        $detallePresupuesto->DESCCENTRO5 = $request->DESCCENTRO5;
        $detallePresupuesto->ANULADO = $request->ANULADODP;
        if ($detallePresupuesto->ANULADO === null) {
            $detallePresupuesto->ANULADO = 0;
        }

        $detallePresupuesto->save();

        return Redirect::to('presupuestos/' . $request->PRESUPUESTO_ID . '-' . $request->TIPO_GASTO . '/edit' );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $unidad = TipoGasto::select('cat_unidadmedida.DESCRIPCION')
                             ->join('cat_subcategoria_tipogasto', 'cat_subcategoria_tipogasto.TIPOGASTO_ID', '=', 'cat_tipogasto.ID' )
                             ->join('cat_unidadmedida', 'cat_unidadmedida.ID', '=', 'cat_subcategoria_tipogasto.UNIDAD_MEDIDA_ID')
                             ->where('cat_tipogasto.ID', '=', $id)
                             ->where('cat_unidadmedida.ANULADO', '=', 0)
                             ->first();
        //dd($unidad->DESCRIPCION);
        return $unidad->DESCRIPCION;
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
        $tipo = $param[1];

        $empresa_id = Session::get('empresa');

        $detallePresupuesto = DetallePresupuesto::findOrFail($presupuesto_id);
        
        $frecuencia = FrecuenciaTiempo::lists('DESCRIPCION', 'ID')
                                        ->toArray();

         $tipoGasto = TipoGasto::where('EMPRESA_ID', '=', $empresa_id)
                                        ->where('ANULADO', '=', 0)
                                        ->lists('DESCRIPCION', 'ID')                                        
                                        ->toArray();

        $tipoAsignacion = TipoAsignacion::lists('DESCRIPCION', 'ID')
            ->toArray();

        if ($tipo == 'Rutas') {
            $rutaPresupuesto = 'presupuestos.edit';
        } else {
            $rutaPresupuesto = 'presupuestos.edit';
        }
        //dd($detallePresupuesto->TIPOASIGNACION_ID);


        return view('detallePresupuestos.edit', compact('detallePresupuesto', 'frecuencia', 'tipoGasto', 'tipoAsignacion', 'rutaPresupuesto', 'tipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateDetallePresupuestoRequest $request, $id)
    {   
        if ($request->ANULADO === null) {
            $request->ANULADO = 0;
        }
        DetallePresupuesto::where('ID', $id)
                ->update(['PRESUPUESTO_ID' => $request->PRESUPUESTO_ID, 'TIPOGASTO_ID' => $request->TIPOGASTO_ID, 'FRECUENCIATIEMPO_ID' => $request->FRECUENCIATIEMPO_ID,
                          'MONTO' => $request->MONTO, 'CENTROCOSTO1' => $request->CENTROCOSTO1, 'CENTROCOSTO2' => $request->CESTROCOSTO2, 'CENTROCOSTO3' => $request->CENTROCOSTO3,
                          'CENTROCOSTO4' => $request->CENTROCOSTO4, 'CENTROCOSTO5' => $request->CESTROCOSTO5, 'TIPOASIGNACION_ID' => $request->TIPOASIGNACION_ID, 'ANULADO' => $request->ANULADO,
                          'DESCCENTRO1' => $request->DESCCENTRO1, 'DESCCENTRO2' => $request->DESCCENTRO2, 'DESCCENTRO3' => $request->DESCCENTRO3, 'DESCCENTRO4' => $request->DESCCENTRO4,
                          'DESCCENTRO5' => $request->DESCCENTRO5, 'PROYECTO' => $request->PROYECTO, 'DESCPROYECTO' => $request->DESCPROYECTO]);

        return Redirect::to('presupuestos/' . $request->PRESUPUESTO_ID . '-' . $request->TIPO_GASTO . '/edit' );
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
        $param = explode('-', $id);
        $id = $param[0];
        $presupuesto_id = $param[1];

        $anulado = DetallePresupuesto::where('id', '=', $id)->pluck('anulado');
       
            if ($anulado == 1) {
                DetallePresupuesto::where('id', $id)
                            ->update(['ANULADO' => 0]);
                $anular = 'No';
            } else {
                DetallePresupuesto::where('id', $id)
                ->update(['ANULADO' => 1]);            
                $anular = 'Si';
            }        
            return $anular;     
        /*DetallePresupuesto::where('ID', $id)
            ->update(['ANULADO' => 1]);

        return 1; //Redirect::to('presupuestos/' . $presupuesto_id . '/edit');*/
    }

}

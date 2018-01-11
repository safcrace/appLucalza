<?php

namespace App\Http\Controllers;

use App\Moneda;
use App\TasaCambio;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\CreateMonedaRequest;

class MonedaController extends Controller
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
        $monedas = Moneda::select('*')                            
                            ->paginate(10);

        return view('monedas.index', compact('monedas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('monedas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMonedaRequest $request)
    {
         /** Se verifica que no exista Codigo de Empresa */
         $existeCodigo = Moneda::select('ID')->where('CLAVE', '=', $request->CLAVE)->first();
         
        if ($existeCodigo) {
            return back()->withInput()->with('info', 'El Código ya existe en la Base de Datos.');
        }

        $moneda = new Moneda();
        $moneda->CLAVE = $request->CLAVE;
        $moneda->DESCRIPCION = $request->DESCRIPCION;
        $moneda->ANULADO = $request->ANULADO;
        if ($moneda->ANULADO === null) {
            $moneda->ANULADO = 0;
        }

        $moneda->save();


        return redirect::to('monedas/' . $moneda->id . '/edit');
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
        $moneda = Moneda::findOrFail($id);

        $tasaCambio = TasaCambio::select('cat_tasacambio.ID', 'cat_tasacambio.MONEDA_ID', 'cat_tasacambio.FECHA', 'cat_tasacambio.COMPRA', 'cat_tasacambio.ANULADO')                                  
                                  ->where('cat_tasacambio.MONEDA_ID', '=', $id)->paginate(5);


        return view('monedas.edit', compact('moneda','tasaCambio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateMonedaRequest $request, $id)
    {
        $moneda = Moneda::findOrFail($id);
        //$moneda->fill($request->all());
        //$moneda->ID = $request->ID;

        $moneda->CLAVE = $request->CLAVE;
        $moneda->DESCRIPCION = $request->DESCRIPCION;
        $moneda->ANULADO = $request->ANULADO;

        if ($moneda->ANULADO === null) {
            $moneda->ANULADO = 0;
        }
        //dd($moneda);
        $moneda->save();

        Moneda::where('ID', $moneda->ID)
          ->update(['CLAVE' => $request->CLAVE, 'DESCRIPCION' => $request->DESCRIPCION, 'ANULADO' => $moneda->ANULADO]);
        //dd('se supone que ya grabo');
        return Redirect::to('monedas');
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
        /** Verifica si moneda esta asignada a una empresa **/
        $monedaActiva = DB::table('cat_moneda')
                                    ->join('cat_empresa', 'cat_empresa.MONEDA_ID', '=', 'cat_moneda.ID')
                                    ->where('cat_moneda.ID', '=', $id)
                                    ->count();

        if($monedaActiva == 0) {
            $anulado = Moneda::where('id', '=', $id)->pluck('anulado');
       
            if ($anulado == 1) {
                Moneda::where('id', $id)
                            ->update(['ANULADO' => 0]);
                $anular = 'No';
            } else {
                Moneda::where('id', $id)
                ->update(['ANULADO' => 1]);            
                $anular = 'Si';
            }        
            return $anular;
        } else {            
            return  'La Moneda no se puede anular, pertenece a una Empresa Activa.';//Redirect::to('monedas');
        }
    }

    public function cargaArchivo(Request $request, $id)
    {   
        $moneda = Moneda::find($id);

        
        if(($request->fechaOrigen === '') || ($request->tasaCambio === '')) {
            Session::flash('info', '¡Los campos Fecha y Tasa de Cambio son Obligatorios!');
            return back()->withInput();
        }

        $existe = TasaCambio::where('MONEDA_ID', '=', $id)->where('FECHA', '=', $request->fechaOrigen)->first();

        if($existe) {
            Session::flash('info', '¡No se puede ingresar dos tipos de cambio con la misma fecha!');
            return back()->withInput();
        } else{
            $tasaCambio = new TasaCambio();

            $tasaCambio->moneda_id = $moneda->ID;

            $tasaCambio->FECHA = $request->fechaOrigen;
            $tasaCambio->COMPRA = $request->tasaCambio;

            $tasaCambio->ANULADO = $request->ANULADOTC;
            if ($tasaCambio->ANULADO === null) {
                $tasaCambio->ANULADO = 0;
            }

            $tasaCambio->save();
            
            return redirect::to('monedas/' . $id . '/edit');
        }
    }
}
/** Proceso para importar desde Excel 
 $file = $request->file('tasasCambio');
$originalName = 'Moneda-' . $id . '-' . $file->getClientOriginalName();
$almacenado = Storage::disk('tasasCambio')->put($originalName,  \File::get($file) );
$rutaAlmacenado = storage_path('tasasCambio') . '/' . $originalName;

if($almacenado) {
    Excel::selectSheetsByIndex(0)->load($rutaAlmacenado, function($hoja) {
        $hoja->each(function($fila) {
            $tasa = TasaCambio::where('FECHA', '=', $fila->fecha)->where('MONEDA_ID', '=', $fila->moneda_id)->where('COMPRA', '=', $fila->compra)->first();
            if(count($tasa) == 0) {
                $tasaCambio = new TasaCambio();
                $tasaCambio->MONEDA_ID = $fila->moneda_id;
                $tasaCambio->FECHA = $fila->fecha;
                $tasaCambio->COMPRA = $fila->compra;
                $tasaCambio->ANULADO = $fila->anulado;

                $tasaCambio->save();
            }
        });

    });
}
**/

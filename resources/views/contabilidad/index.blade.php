@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-primary">
                  <div class="panel-heading panel-title" style="height: 65px">Revisión Liquidaciones Contabilidad
                      <button type="button" class="btn btn-default text-right" style="border-color: white; float: right"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                  </div>


                  <div class="panel-body">

                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">No.</th>
                         <th class="text-center">Fecha Inicio</th>
                         <th class="text-center">Fecha Final</th>
                         <th class="text-center">Vendedor</th>
                         <th class="text-center">Ruta</th>
                         <th class="text-center">Monto</th>
                       </thead>
                       <tbody>

                           @foreach ($liquidaciones as $liquidacion)

                               <tr>

                                     <td><a href="{{ route('showLiquidacionRev', $liquidacion->ID) }}">{{ $liquidacion->ID }}</a></td>
                                     <td><a href="{{ route('showLiquidacionRev', $liquidacion->ID) }}">{{ $liquidacion->FECHA_INICIO->format('d-m-Y') }}</a></td>
                                     <td><a href="{{ route('showLiquidacionRev', $liquidacion->ID) }}">{{ $liquidacion->FECHA_FINAL->format('d-m-Y') }}</a></td>
                                     <td><a href="{{ route('showLiquidacionRev', $liquidacion->ID) }}">{{ $liquidacion->USUARIO }}</a></td>
                                     <td><a href="{{ route('showLiquidacionRev', $liquidacion->ID) }}">{{ $liquidacion->RUTA }}</a></td>
                                     <TD><a href="{{ route('showLiquidacionRev', $liquidacion->ID) }}">
                                       {{ App\Factura::where('LIQUIDACION_ID', '=', $liquidacion->ID)->sum('TOTAL')}}</a></td>
                               </tr>

                            @endforeach


                       </tbody>


                      </table>

                      <div class="text-center">
                      {!!$liquidaciones->render()!!}
                      </div>
                  </div>
                  </div>


              </div>
        </div>
  </div>
@endsection

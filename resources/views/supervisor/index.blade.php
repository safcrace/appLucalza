@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading panel-title">Revisión Liquidaciones Supervisor</div>


                  <div class="panel-body">

                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">No.</th>
                         <th class="text-center">Fecha</th>
                         <th class="text-center">Vendedor</th>
                         <th class="text-center">Ruta</th>
                         <th class="text-center">Monto</th>
                       </thead>
                       <tbody>

                           @foreach ($liquidaciones as $liquidacion)

                               <tr>

                                     <td><a href="{{ route('showLiquidacion', $liquidacion->ID) }}">{{ $liquidacion->ID }}</a></td>
                                     <td><a href="{{ route('showLiquidacion', $liquidacion->ID) }}">{{ $liquidacion->FECHA }}</a></td>
                                     <td><a href="{{ route('showLiquidacion', $liquidacion->ID) }}">{{ $liquidacion->USUARIO }}</a></td>
                                     <td><a href="{{ route('showLiquidacion', $liquidacion->ID) }}">{{ $liquidacion->RUTA }}</a></td>
                                     <TD><a href="{{ route('showLiquidacion', $liquidacion->ID) }}">
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

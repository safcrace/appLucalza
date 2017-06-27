@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading">Revisión Liquidaciones Supervisor</div>


                    <div class="panel-body text-right">
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('home') }}"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('liquidaciones.create') }}"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                    </div>


                  <div class="panel-body">

                     <table class="table table-bordered table-striped ">
                       <thead>
                         <th class="text-center">No.</th>
                         <th class="text-center">Fecha</th>
                         <th class="text-center">Vendedor</th>
                         <th class="text-center">Ruta</th>
                         {{--<th class="text-center">Monto</th>--}}
                       </thead>
                       <tbody>

                           @foreach ($liquidaciones as $liquidacion)

                               <tr>

                                     <td><a href="{{ route('showLiquidacion', $liquidacion->ID) }}">{{ $liquidacion->ID }}</a></td>
                                     <td>{{ $liquidacion->FECHA }}</td>
                                     <td>{{ $liquidacion->USUARIO }}</td>
                                     <td>{{ $liquidacion->RUTA }}</td>

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

@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading panel-title">Control de Liquidaciones</div>


                    <div class="panel-body text-right">
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('home') }}"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('liquidaciones.create') }}"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                    </div>


                  <div class="panel-body">

                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">No.</th>
                         <th class="text-center">Fecha</th>
                         <th class="text-center">Ruta</th>
                         <th class="text-center">Monto</th>
                         <th class="text-center">Estatus</th>
                         <th class="text-center">Anular</th>
                       </thead>
                       <tbody>

                           @foreach ($liquidaciones as $liquidacion)
                               <tr>
                                   <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID) }}">{{ $liquidacion->ID }}</td>
                                   <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID) }}">{{ $liquidacion->FECHA }}</td>
                                   <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID) }}">{{ $liquidacion->RUTA }}</td>
                                   <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID) }}">{{ 'Q.' . App\Factura::where('LIQUIDACION_ID', '=', $liquidacion->ID)->sum('TOTAL')}}</td>
                                   <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID) }}">{{ $liquidacion->DESCRIPCION }}</td>
                                   <td class="text-center">
                                     <a href="{{ route('anularEmpresa', $liquidacion->ID) }}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
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

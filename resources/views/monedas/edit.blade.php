@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              {!! Form::model($moneda, ['route' => ['monedas.update', $moneda->ID], 'method' => 'PATCH']) !!}
            <div class="panel panel-default">
                 <div class="panel-heading panel-title" style="height: 65px">Editar Moneda {{ $moneda->ID }}
                     <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('monedas.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                     <button type="submit" class="btn btn-default" style="border-color: white; float: right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                  </div>
                 <div class="panel-body">

                     @include('monedas.partials.fields')

                  {!! Form::close() !!}
                 </div>
               </div>

               {{-- TIPO DE CAMBIO  --}}

               <div class="panel panel-default">
                   <div class="panel-heading panel-title" style="height: 65px">Tipo de Cambio USD
                       <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('createTasa', $moneda->ID) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                   </div>

                   <div class="panel-body text-right">

                   </div>

                   <div class="panel-body">
                      <table class="table table-bordered table-striped ">
                        <thead>
                          <th>Fecha</th>
                          <th>Tasa de Cambio</th>
                          <th>Anulado</th>
                        </thead>
                        <tbody>

                                @foreach ($tasaCambio as $tasa)
                                    <tr>
                                        <td><a href="{{ route('tasaCambio.edit', $tasa->ID) }}">{{ $tasa->FECHA->format('d-m-Y') }}</a></td>
                                        <td><a href="{{ route('tasaCambio.edit', $tasa->ID) }}">{{ $tasa->COMPRA }}</a></td>
                                        <td>
                                            <a href="{{ route('anularTasaCambio', $tasa->ID . '-' . $moneda->ID) }}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                        </td>
                                    </tr>
                                @endforeach

                        </tbody>


                       </table>
                       <div class="text-center">
                         {!!$tasaCambio->render()!!}
                       </div>

                   </div>
                   </div>


              </div>
        </div>
  </div>
@endsection

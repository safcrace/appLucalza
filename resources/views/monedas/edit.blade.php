@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
            <div class="panel panel-default">
                 <div class="panel-heading panel-title">
                    Editar Moneda {{ $moneda->ID }}

                  </div>
                 <div class="panel-body">

                   {!! Form::model($moneda, ['route' => ['monedas.update', $moneda->ID], 'method' => 'PATCH']) !!}

                     <div class="panel-body text-right">
                       <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('monedas.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>

                       <button type="submit" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                     </div>
                     @include('monedas.partials.fields')

                  {!! Form::close() !!}
                 </div>
               </div>

               {{-- TIPO DE CAMBIO  --}}

               <div class="panel panel-default">
                   <div class="panel-heading panel-title">Tipo de Cambio USD</div>

                   <div class="panel-body text-right">
                     <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('createTasa', $moneda->ID) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                   </div>

                   <div class="panel-body">
                      <table class="table table-bordered table-striped ">
                        <thead>
                          <th>Fecha</th>
                          <th>Tasa de Cambio</th>
                        </thead>
                        <tbody>

                                @foreach ($tasaCambio as $tasa)
                                    <tr>
                                        <td><a href="{{ route('tasaCambio.edit', $tasa->ID) }}">{{ $tasa->FECHA }}</a></td>
                                        <td><a href="{{ route('tasaCambio.edit', $tasa->ID) }}">{{ $tasa->COMPRA }}</a></td>
                                        {{--<td class="text-center">
                                           <a href="{{ route('tasaCambio.edit', $tasa->ID) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                           <a href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                        </td>--}}
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

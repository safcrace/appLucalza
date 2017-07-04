@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
            <div class="panel panel-default">
                 <div class="panel-heading panel-title">
                    Editar Liquidación {{ $liquidacion->ID }}

                  </div>
                 <div class="panel-body">

                   {!! Form::model($liquidacion, ['route' => ['liquidaciones.update', $liquidacion->ID], 'method' => 'PATCH']) !!}

                     <div class="panel-body text-right">
                       <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('liquidaciones.index') }}"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>

                       <button type="submit" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;"></button>
                     </div>
                     @include('liquidaciones.partials.fieldsTwo')
                  {!! Form::close() !!}
                 </div>
               </div>

               <div class="panel panel-default">
                   <div class="panel-heading panel-title">Control de Facturas</div>


                     <div class="panel-body text-right">
                       <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('liquidaciones.index') }}"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                       <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('createFactura', $liquidacion->ID) }}"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                     </div>


                   <div class="panel-body">

                      <table class="table table-bordered table-striped table-hover">
                        <thead>
                          <th class="text-center">Fecha</th>
                          <th class="text-center">Proveedor</th>
                          <th class="text-center">Serie</th>
                          <th class="text-center">Numero</th>
                          <th class="text-center">Tipo de Gasto</th>
                          <th class="text-center">Total</th>
                          <th class="text-center">Anular</th>
                        </thead>
                        <tbody>

                            @foreach ($facturas as $factura)
                                <tr>
                                    <td><a href="{{ route('facturas.edit', $factura->ID) }}">{{ $factura->FECHA_FACTURA->format('d-m-Y') }}</a></td>
                                    <td><a href="{{ route('facturas.edit', $factura->ID) }}">{{ $factura->NOMBRE}}</a></td>
                                    <td><a href="{{ route('facturas.edit', $factura->ID) }}">{{ $factura->SERIE}}</a></td>
                                    <td><a href="{{ route('facturas.edit', $factura->ID) }}">{{ $factura->NUMERO}}</a></td>
                                    <td><a href="{{ route('facturas.edit', $factura->ID) }}">{{ $factura->TIPOGASTO}}</a></td>
                                    <td><a href="{{ route('facturas.edit', $factura->ID) }}">{{ $factura->TOTAL}}</a></td>
                                    <td class="text-center">
                                      <a href="{{ route('anularProveedor', $factura->ID) }}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>


                       </table>

                       <div class="text-center">
                         {{--!!$factura->render()!!--}}
                       </div>
                   </div>
                   </div>

              </div>
        </div>
  </div>
@endsection

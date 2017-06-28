@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
            <div class="panel panel-default">
                 <div class="panel-heading panel-title">
                    Datos Liquidación

                  </div>
                 <div class="panel-body">

                     <div class="panel-body text-right">
                       <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('liquidaciones.index') }}"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>

                     </div>
                     @include('supervisor.detalle')
                  {!! Form::close() !!}
                 </div>
               </div>

               <div class="panel panel-default">
                   <div class="panel-heading">Detalle de Facturación</div>





                   <div class="panel-body">

                      <table class="table table-bordered table-striped ">
                        <thead>
                          <th class="text-center">Fecha</th>
                          <th class="text-center">Proveedor</th>
                          <th class="text-center">Serie</th>
                          <th class="text-center">Numero</th>
                          <th class="text-center">Tipo de Gasto</th>
                          <th class="text-center">Total</th>
                          {{--<th class="text-center">Moneda</th>--}}
                          <th class="text-center">Ver</th>
                        </thead>
                        <tbody>

                            @foreach ($facturas as $factura)
                                <tr>
                                    <td>{{ $factura->FECHA }}</td>
                                    <td>{{ $factura->NOMBRE}}</td>
                                    <td>{{ $factura->SERIE}}</td>
                                    <td>{{ $factura->NUMERO}}</td>
                                    <td>{{ $factura->TIPOGASTO}}</td>
                                    <td>{{ $factura->TOTAL}}</td>
                                    <td class="text-center">
                                      <a href="{{ route('anularProveedor', $factura->ID) }}"><span class="glyphicon glyphicon-eye-open" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>


                       </table>

                       <div class="text-center">
                         {{--!!$factura->render()!!  --}}
                       </div>
                   </div>
                   </div>


              </div>
        </div>
  </div>
@endsection

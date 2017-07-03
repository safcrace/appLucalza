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
                       <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('supervisor') }}"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>

                     </div>

                     @include('contabilidad.detalle')
                  {!! Form::close() !!}
                 </div>
               </div>

               <div class="panel panel-default">
                   <div class="panel-heading panel-title">Detalle de Facturación</div>





                   <div class="panel-body">

                      <table class="table table-bordered table-striped table-hover">
                        <thead>
                          <th class="text-center">Fecha</th>
                          <th class="text-center">Proveedor</th>
                          <th class="text-center">Serie</th>
                          <th class="text-center">Numero</th>
                          <th class="text-center">Tipo de Gasto</th>
                          <th class="text-center">Total</th>
                          {{--<th class="text-center">Moneda</th>--}}
                          <th class="text-center">Ver</th>
                          <th class="text-center">Aprobar</th>
                          <th class="text-center">Corregir</th>
                        </thead>
                        <tbody>

                            @foreach ($facturas as $factura)
                                <tr data-id={{  $factura->NUMERO }}>
                                    <td>{{ $factura->FECHA }}</td>
                                    <td>{{ $factura->NOMBRE}}</td>
                                    <td>{{ $factura->SERIE}}</td>
                                    <td>{{ $factura->NUMERO}}</td>
                                    <td>{{ $factura->TIPOGASTO}}</td>
                                    <td>Q.{{ $factura->TOTAL}}</td>
                                    <td class="text-center">
                                      <a href="#"><span class="glyphicon glyphicon-eye-open" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                    </td>
                                    <td class="text-center">{!! Form::checkbox('Aprobar', false, true, ['class' => 'btn_aprobar']); !!}</td>
                                    <td class="text-center">{!! Form::checkbox('Corregir', true, false, ['class' => 'btn_corregir']); !!}</td>
                                </tr>
                            @endforeach


                        </tbody>


                       </table>

                       <div class="text-center">
                         {{--!!$factura->render()!! | , 'data-toggle' => 'modal', 'data-target' => '#myModal' --}}
                       </div>
                   </div>
                   </div>


              </div>
        </div>
  </div>

  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Factura No. {{$factura->ID}}</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
  </div>
@endsection

@push('scripts')
  <script type="text/javascript">
      var corregirFactura = [];
      $(".btn_corregir").click(function(e){

        //$aprobar = $(this).prev('.btn_aprobar');//.prop( "checked", false );

        var row = $(this).parents('tr');
        row.find('.btn_aprobar').prop( "checked", false );
        var id = row.data('id');
        corregirFactura.push(id);
        alert (corregirFactura);


/*
        alert('Yes??');
        if (valor === true)
        alert (valor);*/
      });

      $(".btn_aprobar").click(function(e){

        var row = $(this).parents('tr');
        var id = row.data('id');
        valor = $(this).val();
        //if (valor === true)
        //alert (valor);
      });
  </script>
@endpush

@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
            {!! Form::model($liquidacion, ['route' => ['liquidaciones.update', $liquidacion->ID], 'method' => 'PATCH']) !!}
            <div class="panel panel-primary">
                 <div class="panel-heading panel-title" style="height: 65px">
                    Editar Liquidación {{ $liquidacion->ID }}
                     <button type="button" class="btn btn-default hidden-xs" style="border-color: white; float: right"><a href="{{ route('indexGeneral', $tipoLiquidacion) }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                     <button type="button" class="btn btn-default btn-sm visible-xs" style="border-color: white; float: right"><a href="{{ route('indexGeneral', $tipoLiquidacion) }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:22px; color: black"></span></a></button>
                     <button type="submit" class="btn btn-default hidden-xs" style="border-color: white; float: right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                     <button type="submit" class="btn btn-default btn-sm visible-xs" style="border-color: white; float: right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:22px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                  </div>
                 <div class="panel-body">

                     @include('liquidaciones.partials.fieldsTwo')

                  {!! Form::close() !!}
                 </div>
               </div>

               <div class="panel panel-primary">
                   <div class="panel-heading panel-title" style="height: 65px">Control de Facturas
                       <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('createFactura', $liquidacion->ID . '-' . $tipoLiquidacion) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                   </div>

                   <div class="panel-body">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped table-hover">
                        <thead>
                          <th class="text-center">Fecha</th>
                          <th class="text-center">Proveedor</th>
                          <th class="text-center">Serie</th>
                          <th class="text-center">Numero</th>
                          <th class="text-center">Tipo de Gasto</th>
                          <th class="text-center">Total</th>
                          <th class="text-center">No Aplica Pago</th>
                          <th class="text-center">Estado</th>
                          <th class="text-center">Anular</th>
                        </thead>
                        <tbody>

                            @foreach ($facturas as $factura)
                                <tr data-id="{{ $factura->ID }}" data-monto="{{ $factura->TOTAL }}">
                                    <td><a href="{{ route('facturas.edit', $liquidacion->ID . '-' . $factura->ID . '-' . $tipoLiquidacion) }}">{{ $factura->FECHA_FACTURA->format('d-m-Y') }}</a></td>
                                    <td><a href="{{ route('facturas.edit', $liquidacion->ID . '-' . $factura->ID . '-' . $tipoLiquidacion) }}">{{ $factura->NOMBRE}}</a></td>
                                    <td><a href="{{ route('facturas.edit', $liquidacion->ID . '-' . $factura->ID . '-' . $tipoLiquidacion) }}">{{ $factura->SERIE}}</a></td>
                                    @if($factura->CORRECCION == 1)
                                        <td style="background-color: red;"><a href="{{ route('facturas.edit', $liquidacion->ID . '-' . $factura->ID . '-' . $tipoLiquidacion) }}" style="text-decoration: none; color: #FFF">{{ $factura->NUMERO}}</a></td>
                                    @else
                                        <td><a href="{{ route('facturas.edit', $liquidacion->ID . '-' . $factura->ID . '-' . $tipoLiquidacion) }}">{{ $factura->NUMERO}}</a></td>
                                    @endif
                                    <td><a href="{{ route('facturas.edit', $liquidacion->ID . '-' . $factura->ID . '-' . $tipoLiquidacion) }}">{{ $factura->TIPOGASTO}}</a></td>
                                    <td><a href="{{ route('facturas.edit', $liquidacion->ID . '-' . $factura->ID . '-' . $tipoLiquidacion) }}">{{ $factura->TOTAL}}</a></td>
                                    <td><a href="{{ route('facturas.edit', $liquidacion->ID . '-' . $factura->ID . '-' . $tipoLiquidacion) }}">{{ ($factura->MONTO_REMANENTE)?$factura->MONTO_REMANENTE:'N/A' }}</a></td>
                                    <td class="text-center"><a href="{{ route('facturas.edit', $factura->ID ) }}">{{ ($factura->ANULADO)?'SI':'' }}</a></td>
                                    <td class="text-center">
                                      <a href="{{ route('anularFactura', $factura->ID) }}" class="btn-delete"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>


                       </table>
                    </div>

                       <div class="text-center">
                         {!! $facturas->render() !!}
                       </div>
                   </div>
                   </div>

              </div>
        </div>

      @include('partials.anular')

  </div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('.btn-delete').click(function (e) {            
            e.preventDefault();
            var row = $(this).parents('tr');
            var id = row.data('id');
            var totalFactura = row.data('monto')           
            vurl = '{{ route('anularFactura') }}';
            vurl = vurl.replace('%7Bid%7D', id);
            /*row.fadeOut();
            $('#myModal').modal('show');
            $('#revertir').click(function () {
                row.show();
            });
            $('#anular').click(function () {
                $('#myModal').modal('hide');*/
                var totalLiquidacion =  $('#totalLiquidacion').val()
                totalLiquidacion = totalLiquidacion.replace(',', '') 
                //totalFactura = totalFactura.replace(',', '')
                //alert(totalLiquidacion)
                //alert(totalFactura)
                var newTotal = totalLiquidacion - totalFactura
                newTotal = new Intl.NumberFormat("en-IN").format(newTotal);
                $('#totalLiquidacion').val(newTotal)
                //alert(newTotal)
                $.ajax({
                    type: 'get',
                    url: vurl,
                    success: function (data) {
                        location.reload();
                    }
                }).fail(function () {
                    alert ('La Factura no pudo ser Eliminada!!!');
                    row.show();
                });
            //})
        });        
    });
</script>
@endpush

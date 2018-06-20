@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-primary">
                  <div class="panel-heading panel-title" style="height: 65px">Control Liquidaciones
                      <button type="button" class="btn btn-default hidden-xs" style="border-color: white; float: right"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      <button type="button" class="btn btn-default btn-xs visible-xs" style="border-color: white; float: right"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:24px; color: black"></span></a></button>
                      <button type="button" class="btn btn-default hidden-xs" style="border-color: white; float: right"><a href="{{ route('liquidacionCreate', $tipoLiquidacion ) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      <button type="button" class="btn btn-default btn-xs visible-xs" style="border-color: white; float: right"><a href="{{ route('liquidacionCreate', $tipoLiquidacion ) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:24px; color: black"></span></a></button>
                  </div>

                  <div class="panel-body">
                    <div class="table-responsive"> 

                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">No.</th>
                         <th class="text-center">Fecha Inicio</th>
                         <th class="text-center">Fecha Final</th>
                         <th class="text-center">{{ $tipoLiquidacion }}</th>
                         <th class="text-center">Monto</th>
                         <th class="text-center">Estatus</th>
                         <th class="text-center">Anulado</th>
                         <th class="text-center">Anular</th>
                       </thead>
                       <tbody>

                           @foreach ($liquidaciones as $liquidacion)
                               <tr data-id="{{  $liquidacion->ID }}">
                                   <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID . '-' . $tipoLiquidacion) }}">{{ $liquidacion->ID }}</td>
                                   <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID . '-' . $tipoLiquidacion) }}">{{ $liquidacion->FECHA_INICIO->format('d-m-Y') }}</td>
                                   <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID . '-' . $tipoLiquidacion) }}">{{ $liquidacion->FECHA_FINAL->format('d-m-Y') }}</td>
                                   <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID . '-' . $tipoLiquidacion) }}">{{ $liquidacion->RUTA }}</td>
                                   <td class="text-right"><a href="{{ route('liquidaciones.edit', $liquidacion->ID . '-' . $tipoLiquidacion) }}">{{ App\Factura::where('LIQUIDACION_ID', '=', $liquidacion->ID)->where('ANULADO', '=', 0)->sum('TOTAL')}}</td>
                                   @if($liquidacion->DESCRIPCION == 'En Corrección')
                                        <td style="background-color: red; color: #ffffff;"><a href="{{ route('liquidaciones.edit', $liquidacion->ID . '-' . $tipoLiquidacion) }}" style="text-decoration:none; color: white;">{{ $liquidacion->DESCRIPCION }}</td>
                                   @else
                                        <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID . '-' . $tipoLiquidacion) }}">{{ $liquidacion->DESCRIPCION }}</td>
                                   @endif
                                   <td class="text-center"><a href="{{ route('liquidaciones.edit', $liquidacion->ID ) }}">{{ ($liquidacion->ANULADO)?'SI':'' }}</a></td>
                                   <td class="text-center">
                                     <a href="{{ route('anularLiquidacion', $liquidacion->ID) }}" class="btn-delete"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                               </tr>
                            @endforeach


                       </tbody>


                      </table>
                    </div>
                      <div class="text-center">
                      {!!$liquidaciones->render()!!}
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
            vurl = '{{ route('anularLiquidacion') }}';
            vurl = vurl.replace('%7Bid%7D', id);
            /*row.fadeOut();
            $('#myModal').modal('show');
            $('#revertir').click(function () {
                row.show();
            });
            $('#anular').click(function () {
                $('#myModal').modal('hide');*/
                $.ajax({
                    type: 'get',
                    url: vurl,
                    success: function (data) {
                        location.reload();
                    }
                }).fail(function () {
                    alert ('La Liquidación no pudo ser Eliminada!!!');
                    row.show();
                });
            //})
        });
    });
</script>
@endpush
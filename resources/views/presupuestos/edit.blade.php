@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
            {!! Form::model($presupuesto, ['route' => ['presupuestos.update', $presupuesto->ID], 'method' => 'PATCH']) !!}
            <div class="panel panel-primary">
                 <div class="panel-heading panel-title" style="height: 65px">
                    Editar Presupuesto {{ $presupuesto->ID }}
                     <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route($rutaPresupuesto) }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                     <button type="submit" class="btn btn-default" style="border-color: white; float: right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                  </div>
                 <div class="panel-body">

                     @include('presupuestos.partials.fields')

                  {!! Form::close() !!}
                 </div>
               </div>
               
              @if($presupuesto->ASIGNACION_MENSUAL == null || $presupuesto->ASIGNACION_MENSUAL == 0 )

               <div class="panel panel-primary">
                   <div class="panel-heading panel-title" style="height: 65px">Detalle Presupuesto
                       <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('createDetalle', $presupuesto->ID . '-' . $tipoGasto) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                   </div>

                   <div class="panel-body">

                      <table class="table table-bordered table-striped table-hover">
                        <thead>
                          <th class="text-center">Tipo Gasto</th>
                          <th class="text-center">Frecuencia</th>
                          <th class="text-center">Asignación</th>                        
                          <th class="text-center">Anular</th>
                        </thead>
                        <tbody>

                            @foreach ($detallePresupuestos as $detallePresupuesto)
                                <tr data-id="{{ $detallePresupuesto->ID . '-' . $presupuesto->ID }}">
                                    <td><a href="{{ route('detallePresupuestos.edit', $detallePresupuesto->ID . '-' . $tipoGasto) }}">{{ $detallePresupuesto->TIPOGASTO}}</a></td>
                                    <td><a href="{{ route('detallePresupuestos.edit', $detallePresupuesto->ID . '-' . $tipoGasto) }}">{{ $detallePresupuesto->FRECUENCIA}}</a></td>
                                    <td><a href="{{ route('detallePresupuestos.edit', $detallePresupuesto->ID . '-' . $tipoGasto) }}">{{ $detallePresupuesto->MONTO}}</a></td>
                                    <td class="text-center">
                                      <a href="{{ route('anularDetallePresupuesto', $detallePresupuesto->ID . '-' . $presupuesto->ID) }}" class="btn-delete"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>


                       </table>

                       <div class="text-center">
                         {!!$detallePresupuestos->render()!!}
                       </div>
                   </div>
                   </div>

              </div>
          @endif
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
            vurl = '{{ route('anularDetallePresupuesto') }}';
            vurl = vurl.replace('%7Bid%7D', id);
            row.fadeOut();
            $('#myModal').modal('show');
            $('#revertir').click(function () {
                row.show();
            });
            $('#anular').click(function () {                 
                $('#myModal').modal('hide');
                $.ajax({
                    type: 'get',
                    url: vurl,
                    success: function (data) {
                        if(data == 1) {
                            console.log('El Detalle del Presupuesto fue Eliminado Exitosamente!!!.');
                        } else {
                            alert('El Detalle del Presupuesto no fue Eliminado!!!');
                        }
                    }
                }).fail(function () {
                    alert ('El Detalle del Presupuesto no pudo ser Eliminado!!!');
                    row.show();
                });
            })
        });
    });
</script>
@endpush

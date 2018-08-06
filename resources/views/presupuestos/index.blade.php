@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-primary">
                  <div class="panel-heading panel-title" style="height: 65px">Control de Presupuesto
                      <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('presupuestoCreate', $tipoGasto ) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                  </div>

                  <div class="panel-body">

                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">Código</th>
                         <th class="text-center">Usuario</th>
                         <th class="text-center">{{ ($tipoGasto == 1) ? 'Rutas' : 'Gastos' }}</th>
                         <th class="text-center">Del</th>
                         <th class="text-center">Al</th>
                         <th class="text-center">Estado</th>
                         <th class="text-center">Anular</th>
                       </thead>
                       <tbody>

                           @foreach ($presupuestos as $presupuesto)
                               <tr data-id="{{ $presupuesto->ID }}">
                                   <td><a href="{{ route('presupuestos.edit', $presupuesto->ID . '-' . $tipoGasto) }}">{{ $presupuesto->ID }}</a></td>
                                   <td><a href="{{ route('presupuestos.edit', $presupuesto->ID . '-' . $tipoGasto) }}">{{ $presupuesto->USUARIO }}</a></td>
                                   <td><a href="{{ route('presupuestos.edit', $presupuesto->ID . '-' . $tipoGasto) }}">{{ $presupuesto->RUTA }}</a></td>
                                   <td><a href="{{ route('presupuestos.edit', $presupuesto->ID . '-' . $tipoGasto) }}">{{ $presupuesto->VIGENCIA_INICIO->format('d-m-Y') }}</a></td>
                                   <td><a href="{{ route('presupuestos.edit', $presupuesto->ID . '-' . $tipoGasto) }}">{{ $presupuesto->VIGENCIA_FINAL->format('d-m-Y') }}</a></td>
                                   <td class="text-center"><a href="{{ route('presupuestos.edit', $presupuesto->ID ) }}">{{ ($presupuesto->ANULADO)?'ANULADO':'' }}</a></td>
                                   <td class="text-center">
                                     <a href="{{ route('anularPresupuesto', $presupuesto->ID) }}" class="btn-delete"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                               </tr>
                           @endforeach


                       </tbody>


                      </table>

                      <div class="text-center">
                        {!!$presupuestos->render()!!}
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
            vurl = '{{ route('anularPresupuesto') }}';
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
                    alert ('El Presupuesto no pudo ser Eliminado!!!');
                    row.show();
                });
            //})
        });
    });
</script>
@endpush
@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading panel-title" style="height: 65px">Proveedores por Empresa: <span style="font-weight: 700">{{ $nombreEmpresa->DESCRIPCION }}</span>
                      <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('empresas.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('createProveedor', $empresa_id) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                  </div>

                  <div class="panel-body">

                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">Identificador Tributario</th>
                         <th class="text-center">Proveedor</th>
                         <th class="text-center">Anular</th>
                       </thead>
                       <tbody>

                           @foreach ($proveedores as $proveedor)
                               <tr data-id="{{ $proveedor->ID . '-' . $empresa_id }}">
                                   <td><a href="{{ route('proveedores.edit', $proveedor->ID) }}">{{ $proveedor->IDENTIFICADOR_TRIBUTARIO}}</a></td>
                                   <td><a href="{{ route('proveedores.edit', $proveedor->ID) }}">{{ $proveedor->NOMBRE}}</a></td>
                                   <td class="text-center">
                                     <a href="{{ route('anularProveedor', $proveedor->ID . '-' . $empresa_id) }}" class="btn-delete"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                               </tr>
                           @endforeach


                       </tbody>


                      </table>

                      <div class="text-center">
                        {!!$proveedores->render()!!}
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
            vurl = '{{ route('anularProveedor') }}';
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
                            console.log('El Proveedor fue Eliminado Exitosamente!!!.');
                        } else {
                            alert('El Proveedor no fue Eliminado!!!');
                        }
                    }
                }).fail(function () {
                    alert ('El Proveedor no pudo ser Eliminado!!!');
                    row.show();
                });
            })
        });
    });
</script>
@endpush
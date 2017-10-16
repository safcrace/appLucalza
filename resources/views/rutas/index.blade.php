@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-primary">
                  <div class="panel-heading panel-title" style="height: 65px">{{ $descripcion }} por Empresa: <span style="font-weight: 700">{{ $nombreEmpresa->DESCRIPCION }}</span>
                      <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('empresas.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('createRuta', $empresa_id . '-' . $descripcion) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                  </div>

                  <div class="panel-body">

                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">Código</th>
                         <th class="text-center">{{ $descripcion }}</th>
                         <th class="text-center">Anular</th>
                       </thead>
                       <tbody>

                           @foreach ($rutas as $ruta)
                               <tr data-id="{{ $ruta->ID . '-' . $empresa_id }}">
                                   <td><a href="{{ route('rutas.edit', $ruta->ID . '-' . $descripcion) }}">{{ $ruta->CLAVE}}</a></td>
                                   <td><a href="{{ route('rutas.edit', $ruta->ID . '-' . $descripcion) }}">{{ $ruta->DESCRIPCION}}</a></td>
                                   <td class="text-center">
                                     <a href="{{ route('anularRuta', $ruta->ID . '-' . $empresa_id) }}" class="btn-delete"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                               </tr>
                           @endforeach


                       </tbody>


                      </table>

                      <div class="text-center">
                        {!!$rutas->render()!!}
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
            vurl = '{{ route('anularRuta') }}';
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
                            console.log('La Ruta fue Eliminada Exitosamente!!!.');
                        } else {
                            alert('La Ruta no se puede eliminar, pertenece a un Presupuesto Activo.')
                            row.show();
                        }
                    }
                }).fail(function () {
                    alert ('El Usuario no pudo ser Eliminado!!!');
                    row.show();
                });
            })
        });
    });
</script>
@endpush
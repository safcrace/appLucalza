@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-primary">
                  <div class="panel-heading panel-title">Control de Permisos</div>


                    <div class="panel-body text-right">
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('permisos.create') }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                    </div>


                  <div class="panel-body">

                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">Código</th>
                         <th class="text-center">Nombre</th>
                         <th class="text-center">Eliminar</th>
                       </thead>
                       <tbody>

                           @foreach ($permisos as $permiso)
                               <tr data-id="{{ $permiso->id }}">
                                   <td><a href="{{ route('permisos.edit', $permiso->id) }}">{{ $permiso->id}}</a></td>
                                   <td><a href="{{ route('permisos.edit', $permiso->id) }}">{{ $permiso->name}}</a></td>
                                   <td class="text-center">
                                     <a href="{{ route('anularPermiso', $permiso->id) }}" class="btn-delete"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                               </tr>
                           @endforeach


                       </tbody>


                      </table>

                      <div class="text-center">
                        {!!$permisos->render()!!}
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
            vurl = '{{ route('anularPermiso') }}';
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
                            console.log('El Permiso fue Eliminado Exitosamente!!!.');
                        } else {
                            alert('El Permiso no fue Eliminado!!!');
                        }
                    }
                }).fail(function () {
                    alert ('El Permiso no pudo ser Eliminado!!!');
                    row.show();
                });
            })
        });
    });
</script>
@endpush

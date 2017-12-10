@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-primary">
                  <div class="panel-heading panel-title" style="height: 65px">Control de Usuarios</span>
                      {{--  @if (auth()->user()->hasRole('superAdmin', 'master'))  --}}
                      @if(isset($id_empresa))
                          <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('empresas.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      @else
                          <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      @endif
                      @can('crear usuario')
                        @if(isset($id_empresa))

                        @else
                            <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('usuarios.create') }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>                        
                        @endif
                      @endcan
                  </div>


                  <div class="panel-body">

                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">Código</th>
                         <th class="text-center">Nombre</th>
                         <th class="text-center">Correo Electrónico</th>
                         <th>Anulado</th>
                         <th class="text-center">Anular</th>
                         @if(isset($id_empresa))
                            @can('ver rutas')
                            <th class="text-center">Módulo</th>
                            @endcan
                         @endif
                       </thead>
                       <tbody>

                           @foreach ($users as $user)
                               <tr data-id="{{ $user->id }}">
                                   <td><a href="{{ route('usuarios.edit', $user->id) }}">{{ $user->id}}</a></td>
                                   <td><a href="{{ route('usuarios.edit', $user->id) }}">{{ $user->nombre}}</a></td>
                                   <td><a href="{{ route('usuarios.edit', $user->id ) }}">{{ $user->email}}</a></td>
                                    @if (auth()->user()->hasRole('superAdmin', 'master', 'administrador'))
                                        <td class="text-center"><a href="{{ route('usuarios.edit', $user->id ) }}" id="anulado">{{ ($user->anulado)?'ANULADO':'' }}</a></td>
                                    @endif
                                   <td class="text-center">
                                     <a href="{{ route('anularUsuario') }}" class="btn-delete"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                                   @if(isset($id_empresa))
                                        @can('ver rutas')
                                            <td class="text-center">
                                            <a href="{{ route('indexRutasUsuario', $user->id . '-Rutas' . '-' . $id_empresa) }}"><button type="button" class="btn btn-primary btn-sm">Rutas</button></a>
                                            <a href="{{ route('indexRutasUsuario', $user->id . '-Otros Gastos' . '-' . $id_empresa) }}"><button type="button" class="btn btn-primary btn-sm">Otros Gastos</button></a>
                                            </td>
                                        @endcan
                                   @endif
                               </tr>
                           @endforeach


                       </tbody>


                      </table>

                      <div class="text-center">
                        {!!$users->render()!!}
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
            vurl = '{{ route('anularUsuario') }}';
            vurl = vurl.replace('%7Bid%7D', id);
            
            //row.fadeOut();
            //$('#myModal').modal('show');
            //$('#revertir').click(function () {
            //    row.show();
            //});
            //$('#anular').click(function () {
            //    $('#myModal').modal('hide');
                $.ajax({
                    type: 'get',
                    url: vurl,
                    success: function (data) {                                               
                        location.reload(); 
                    }
                }).fail(function () {
                    alert ('El Usuario no pudo ser Eliminado!!!');                    
                });
            //})
        });
        $('#verPassword').click(function() {
            alert('Hola')
        })
    });
</script>
@endpush


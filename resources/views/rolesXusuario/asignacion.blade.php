@extends('layouts.app')


@include('layouts.headerTwo')
@section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  {!! Form::open(['route' => 'creaRolesUsuario', 'method' => 'POST']) !!}
                  <div class="panel-heading panel-title" style="height: 65px">Asignación de Roles a Usuarios
                    <button type="button" class="btn btn-default text-right" style="border-color: white; float: right"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                    <button type="submit" class="btn btn-default" style="border-color: white; float: right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                  </div>

                  <div class="panel-body">                      

                      @include('rolesXusuario.partials.fields')

                   {!! Form::close() !!}

                  </div>
              </div>


              </div>
        </div>
  </div>

@endsection


@push('scripts')
  <script type="text/javascript">
      $(document).ready(function () {

        var usuario = $('#usuario').val();


        $('#usuario').change(function () {

          var usuario = $('#usuario').val();
          vurl = '{{ route('roles.show')}}' + usuario;
          vurl = vurl.replace('%7Broles%7D', '');
          location.href = vurl;
        });

        $('#supervisor').select2();
            placeholder: 'Seleccione un Supervisor';
        });

        $('#permisos').select2({
          placeholder: "Seleccione un Permiso",
          allowClear: true
        });

        $('#roles').select2({
          placeholder: "Seleccione un Rol",
          allowClear: true
        });

  </script>
@endpush

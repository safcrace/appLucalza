@extends('layouts.app')


@include('layouts.headerTwo')
@section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-primary">
                  {!! Form::open(['route' => 'creaPermisosRole', 'method' => 'POST']) !!}      
                  <div class="panel-heading panel-title" style="height: 65px">Asignación de Permisos a Roles
                    <button type="button" class="btn btn-default text-right" style="border-color: white; float: right"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                    <button type="submit" class="btn btn-default" style="border-color: white; float: right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                  </div>

                  <div class="panel-body">

                      @include('permisosXroles.partials.fields')

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
        var role = $('#role').val();


        $('#role').change(function () {

          var role = $('#role').val();
          vurl = '{{ route('permisos.show')}}' + role;
          vurl = vurl.replace('%7Bpermisos%7D', '');

          location.href = vurl;

          /*$.ajax({
              type:'get',
              url:vurl,
              success: function(data){
                  alert(data);
                  var seleccionados = "[" + data + "]"
                  $('#permisos').val(data);
                  alert('esto paso!!!');
                  //$('#vendedores').empty().html(data);
              }
          });*/
        });


        $('#supervisor').select2();
            placeholder: 'Seleccione un Supervisor';
        });

        $('#permisos').select2({
          placeholder: "Seleccione un Permiso",
          allowClear: true
        });

        $('#permisosUsuario').select2({
          placeholder: "Seleccione un Permiso",
          allowClear: true
        });

  </script>
@endpush

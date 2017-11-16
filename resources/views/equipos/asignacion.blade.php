@extends('layouts.app')


@include('layouts.headerTwo')
@section('content')
  <div class="container">
      @if(Session::has('validaVendedorSupervisor'))
          <div class="alert alert-danger" role="alert">
              {{ Session::get('validaVendedorSupervisor') }}
          </div>
      @endif
      <div class="row">
          <div class="col-md-12 ">
              {!! Form::open(['route' => 'creaEquipo', 'method' => 'POST']) !!}
              <div class="panel panel-primary">
                  <div class="panel-heading panel-title" style="height: 65px">Asignación de Vendedores a Supervisor
                      <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('empresas.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      <button type="submit" class="btn btn-default" style="border-color: white; float: right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                  </div>

                  <div class="panel-body">

                      @include('equipos.partials.fields')

                   {!! Form::close() !!}

                  </div>
              </div>

              {{-- Usuario Asignados --}}

              <div id="vendedores">

              </div>



              </div>
        </div>
  </div>

@endsection

@push('scripts')
  <script type="text/javascript">
      $(document).ready(function () {
          var supervisor = $('#supervisor').val();
          var empresa = $('#empresa').val();          

          if (supervisor != "") {
              vurl = '{{ route('vendedoresSupervisor')}}'
              vurl = vurl.replace('%7Bid%7D', supervisor + '-' + empresa);              

              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {
                      $('#vendedores').empty().html(data);
                  }
              });
          }


          $('#supervisor').change(function () {
              var supervisor = $('#supervisor').val();
              var empresa = $('#empresa').val();
              vurl = '{{ route('vendedoresSupervisor')}}'
              vurl = vurl.replace('%7Bid%7D', supervisor + '-' + empresa);
              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {
                      $('#vendedores').empty().html(data);
                  }
              });
          });

          $('#supervisor').select2({
              placeholder: 'Seleccione un Supervisor'
          });

          $('#vendedor').select2({
              placeholder: 'Seleccione un Vendedor'
          });
      });
  </script>
@endpush

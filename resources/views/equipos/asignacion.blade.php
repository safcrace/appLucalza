@extends('layouts.app')


@include('layouts.headerTwo')
@section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading panel-title">Asignación de Vendedores a Supervisor</div>

                  <div class="panel-body">

                    {!! Form::open(['route' => 'creaEquipo', 'method' => 'POST']) !!}


                      <div class="panel-body text-right">
                        <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('empresas.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>

                        <button type="submit" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                      </div>

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

        if (supervisor != "" )
        {
          vurl = '{{ route('vendedoresSupervisor')}}'
          vurl = vurl.replace('%7Bid%7D', supervisor);

          $.ajax({
              type:'get',
              url:vurl,
              success: function(data){
                  $('#vendedores').empty().html(data);
              }
          });
        }


        $('#supervisor').change(function () {
          var supervisor = $('#supervisor').val();
          vurl = '{{ route('vendedoresSupervisor')}}'
          vurl = vurl.replace('%7Bid%7D', supervisor);

          $.ajax({
              type:'get',
              url:vurl,
              success: function(data){
                  $('#vendedores').empty().html(data);
              }
          });
        });

        $('#supervisor').select2();
            placeholder: 'Seleccione un Supervisor';
        });

        $('#vendedor').select2();
            placeholder: 'Seleccione un Vendedor';
        });
  </script>
@endpush

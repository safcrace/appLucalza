@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              {!! Form::model($moneda, ['route' => ['monedas.update', $moneda->ID], 'method' => 'PATCH']) !!}
            <div class="panel panel-primary">
                 <div class="panel-heading panel-title" style="height: 65px">Editar Moneda {{ $moneda->ID }}
                     <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('monedas.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                     <button type="submit" class="btn btn-default" style="border-color: white; float: right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                  </div>
                 <div class="panel-body">

                     @include('monedas.partials.fields')

                  {!! Form::close() !!}
                 </div>
               </div>

               {{-- TIPO DE CAMBIO  --}}

               <div class="panel panel-primary">
                   <div class="panel-heading panel-title" style="height: 65px">Tipo de Cambio USD
                       <button type="button" class="btn btn-default" style="border-color: white; float: right" data-toggle="modal" data-target="#myModalA"><a href="#" title="Cargar Archivo"><span class="glyphicon glyphicon-import" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                       <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('createTasa', $moneda->ID) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                   </div>

                   <div class="panel-body text-right">

                   </div>

                   <div class="panel-body">
                      <table class="table table-bordered table-striped ">
                        <thead>
                          <th>Fecha</th>
                          <th>Tasa de Cambio</th>
                          <th>Anulado</th>
                        </thead>
                        <tbody>

                                @foreach ($tasaCambio as $tasa)
                                    <tr data-id="{{ $tasa->ID . '-' . $moneda->ID }}">
                                        <td><a href="{{ route('tasaCambio.edit', $tasa->ID) }}">{{ $tasa->FECHA->format('d-m-Y') }}</a></td>
                                        <td><a href="{{ route('tasaCambio.edit', $tasa->ID) }}">{{ $tasa->COMPRA }}</a></td>
                                        <td>
                                            <a href="{{ route('anularTasaCambio', $tasa->ID . '-' . $moneda->ID) }}" class="btn-delete"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                        </td>
                                    </tr>
                                @endforeach

                        </tbody>


                       </table>
                       <div class="text-center">
                         {!!$tasaCambio->render()!!}
                       </div>

                   </div>
                   </div>


              </div>
        </div>

      @include('partials.anular');

      <div class="modal fade" id="myModalA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">Carga de Tasa de Cambio</h4>
                  </div>

                  <div class="modal-body">

                      {!! Form::open(['route' => ['cargaArchivo', $moneda->ID], 'method' => 'POST']) !!}
                        @include('monedas.tasaCambio.field')

                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>
                      <button type="submit" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;"></button>
                  </div>
                  {!! Form::close() !!}
              </div>
          </div>
      </div>

  </div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {

        $('.btn-delete').click(function (e) {
            e.preventDefault();
            var row = $(this).parents('tr');
            var id = row.data('id');
            vurl = '{{ route('anularTasaCambio') }}';
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
                            console.log('La Tasa de Cambio fue Eliminada Exitosamente!!!.');
                        } else {
                            alert('La Tasa de Cambio fue Eliminada!!!');
                        }
                    }
                }).fail(function () {
                    alert ('La Tasa de Cambio no pudo ser Eliminada!!!');
                });
            })
        });

        $('#cargaTasa').click(function() {            
              vurl = '{{ route('tasaCambio')}}'
              fecha = $('#fechaConsulta').val()  
              if (fecha === '') {
                  alert('Debe ingresar una fecha!')
              }                           
              vurl = vurl.replace('%7Bid%7D', fecha);              
              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {
                      $('#tasaCambio').val(data);                      
                  }
              });
          })
    });
</script>
@endpush
@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
            {!! Form::model($tipoGasto, ['route' => ['tipoGastos.update', $tipoGasto->ID], 'method' => 'PATCH']) !!}
            <div class="panel panel-primary">
                 <div class="panel-heading panel-title" style="height: 65px">
                    Editar Categoría Tipo de Gasto {{ $tipoGasto->DESCRIPCION }}
                     <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('indexTipoGasto', $tipoGasto->EMPRESA_ID) }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                     <button type="submit" class="btn btn-default" style="border-color: white; float: right" id="salvar"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                  </div>
                 <div class="panel-body">

                     @include('tipoGastos.partials.fields')

                  {!! Form::close() !!}
                 </div>
               </div>

              </div>
        </div>

      <div class="panel panel-primary">
          <div class="panel-heading panel-title" style="height: 65px">Subcategoria Tipo de Gasto

              <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('createSubcategoria', $tipoGasto->ID) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
          </div>

          <div class="panel-body text-right">

          </div>

          <div class="panel-body">
              <table class="table table-bordered table-striped ">
                  <thead>
                  <th>Tipo de Gasto</th>
                  <th>Exento</th>
                  <th>Estado</th>
                  <th>Anulado</th>
                  </thead>
                  <tbody>

                  @foreach ($subcategoriaTipoGasto as $tipoCategoria)
                      <tr data-id="{{ $tipoCategoria->ID . '-' . $tipoGasto->ID }}">
                          <td><a href="{{ route('subcategoriaTipoGastos.edit', $tipoCategoria->ID) }}">{{ $tipoCategoria->DESCRIPCION }}</a></td>
                          <td><a href="{{ route('subcategoriaTipoGastos.edit', $tipoCategoria->ID) }}">{{ ($tipoCategoria->EXENTO) ? 'SI' : 'NO' }}</a></td>
                          <td class="text-center"><a href="{{ route('subcategoriaTipoGastos.edit', $tipoCategoria->ID ) }}">{{ ($tipoCategoria->ANULADO)?'ANULADO':'' }}</a></td>  
                          <td>
                              <a href="{{ route('anularSubcategoriaTipoGasto', $tipoCategoria->ID . '-' . $tipoGasto->ID) }}" class="btn-delete"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                          </td>
                      </tr>
                  @endforeach

                  </tbody>


              </table>
              <div class="text-center">

              </div>

          </div>
      </div>


  </div>

  </div>

  @include('partials.anular');

@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
       
        $('.btn-delete').click(function (e) {
            e.preventDefault();
            var row = $(this).parents('tr');
            var id = row.data('id');
            vurl = '{{ route('anularSubcategoriaTipoGasto') }}';
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
                    alert ('La Subcategoria no pudo ser Eliminada!!!');
                });
            //})
        });
    });
</script>
@endpush

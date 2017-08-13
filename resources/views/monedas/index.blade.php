@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-primary">
                  <div class="panel-heading panel-title" style="height: 65px">Control de Monedas
                      <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('monedas.create') }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                  </div>

                    <div class="panel-body text-right">

                    </div>


                  <div class="panel-body">

                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">ID</th>
                         <th class="text-center">Clave</th>
                         <th class="text-center">Descripción</th>
                         <th class="text-center">Anular</th>
                       </thead>
                       <tbody>

                           @foreach ($monedas as $moneda)
                               <tr data-id="{{ $moneda->ID }}">
                                   <td><a href="{{ route('monedas.edit', $moneda->ID) }}">{{ $moneda->ID}}</a></td>
                                   <td><a href="{{ route('monedas.edit', $moneda->ID) }}">{{ $moneda->CLAVE}}</a></td>
                                   <td><a href="{{ route('monedas.edit', $moneda->ID) }}">{{ $moneda->DESCRIPCION}}</a></td>
                                   <td class="text-center">
                                     <a href="{{ route('anular', $moneda->ID) }}" class="btn-delete"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                               </tr>
                           @endforeach


                       </tbody>


                      </table>

                      <div class="text-center">
                        {!!$monedas->render()!!}
                      </div>
                  </div>
                  </div>


              </div>
        </div>

      @include('partials.anular');

  </div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {

        $('.btn-delete').click(function (e) {
            e.preventDefault();
            var row = $(this).parents('tr');
            var id = row.data('id');
            vurl = '{{ route('anular')}}';
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
                        if(data == 'La Moneda no se puede eliminar, pertenece a una Empresa Activa.') {
                            alert('La Moneda no se puede eliminar, pertenece a una Empresa Activa.')
                            row.show();
                        } else {
                            console.log('La Moneda fue Eliminada Exitosamente!!!');
                            location.reload();
                        }
                    }
                }).fail(function () {
                    alert ('La Moneda no fue Eliminada!!!');
                });
            })
        });
    });
</script>
@endpush
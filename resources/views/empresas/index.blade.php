@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row"> 
          <div class="col-md-12 col-xs-12">
              <div class="panel panel-primary">
                  <div class="panel-heading panel-title" style="height: 65px">Control de Empresas
                      <button type="button" class="btn btn-default hidden-xs" style="border-color: white; float: right"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      <button type="button" class="btn btn-default visible-xs" style="border-color: white; float: right"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:24px; color: black"></span></a></button>  
                      @can('crear usuario')
                        <button type="button" class="btn btn-default hidden-xs" style="border-color: white; float: right"><a href="{{ route('empresas.create') }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                        <button type="button" class="btn btn-default visible-xs" style="border-color: white; float: right"><a href="{{ route('empresas.create') }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:24px; color: black"></span></a></button>
                      @endcan
                  </div>


                  <div class="panel-body">
                    <div class="table-responsive"> 
                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">Código</th>
                         <th class="text-center">Nombre</th>
                         <th class="text-center">Estado</th>
                         <th class="text-center">Anular</th>
                         <th class="text-center">Módulo</th>
                         
                       </thead>
                       <tbody>

                           @foreach ($empresas as $empresa)
                               <tr data-id="{{ $empresa->ID }}">
                                   <td><a href="{{ route('empresas.edit', $empresa->ID) }}">{{ $empresa->CLAVE}}</a></td>
                                   <td><a href="{{ route('empresas.edit', $empresa->ID) }}">{{ $empresa->DESCRIPCION}}</a></td>
                                   {{--<td class="text-center">
                                     <a href="{{ route('empresas.edit', $empresa->ID) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>--}}
                                   <td class="text-center"><a href="{{ route('empresas.edit', $empresa->ID ) }}">{{ ($empresa->ANULADO)?'ANULADO':'' }}</a></td>
                                   <td class="text-center">
                                     <a href="{{ route('anularEmpresa', $empresa->ID) }}" class="btn-delete"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                                   <td class="text-center">                                     
                                     @can('ver tipo gasto')
                                       <a href="{{ route('indexTipoGasto', $empresa->ID) }}"><button type="button" class="btn btn-primary btn-sm">Categoría de Gasto</button></a>
                                     @endcan
                                     @can('ver rutas')
                                       <a href="{{ route('indexRuta', $empresa->ID . '-Rutas') }}"><button type="button" class="btn btn-primary btn-sm">Rutas</button></a>
                                       <a href="{{ route('indexRuta', $empresa->ID . '-Otros Gastos') }}"><button type="button" class="btn btn-primary btn-sm">Otros Gastos</button></a>
                                       <a href="{{ route('indexProveedor', $empresa->ID) }}"><button type="button" class="btn btn-primary btn-sm">Proveedores</button></a>
                                       <a href="{{ route('asignaEquipo', $empresa->ID) }}"><button type="button" class="btn btn-primary btn-sm">Equipos</button></a>                                       
                                     @endcan
                                     @can('ver usuarios')
                                       <a href="{{ route('indexEmpresas', $empresa->ID) }}"><button type="button" class="btn btn-primary btn-sm">Usuarios</button></a>
                                     @endcan
                                   </td>
                               </tr>
                           @endforeach


                       </tbody>


                      </table>
                    </div>
                      <div class="text-center">
                        {!!$empresas->render()!!}
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
            vurl = '{{ route('anularEmpresa') }}';
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
                    alert ('La Empresa no pudo ser Eliminada!!!');
                });
            //})
        });
    });
</script>
@endpush
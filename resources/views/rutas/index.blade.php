@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading panel-title" style="height: 65px">Rutas por Empresa: <span style="font-weight: 700">{{ $nombreEmpresa->DESCRIPCION }}</span>
                      <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('empresas.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('createRuta', $empresa_id) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                  </div>

                  <div class="panel-body">

                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">Código</th>
                         <th class="text-center">Ruta</th>
                         <th class="text-center">Anular</th>
                       </thead>
                       <tbody>

                           @foreach ($rutas as $ruta)
                               <tr>
                                   <td><a href="{{ route('rutas.edit', $ruta->ID) }}">{{ $ruta->CLAVE}}</a></td>
                                   <td><a href="{{ route('rutas.edit', $ruta->ID) }}">{{ $ruta->DESCRIPCION}}</a></td>
                                   <td class="text-center">
                                     <a href="{{ route('anularRuta', $ruta->ID . '-' . $empresa_id) }}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                               </tr>
                           @endforeach


                       </tbody>


                      </table>

                      <div class="text-center">
                        {!!$rutas->render()!!}
                      </div>
                  </div>
                  </div>


              </div>
        </div>
  </div>
@endsection

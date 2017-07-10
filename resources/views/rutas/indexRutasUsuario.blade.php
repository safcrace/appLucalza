@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading panel-title">Control de Rutas</div>


                    <div class="panel-body text-right">
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('indexUsuario', $empresa_id) }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('createRutaUsuario', $empresa_id. '-'  . $usuario_id) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
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
                                   <td><a href="{{ route('editUsuarioRuta', $empresa_id . '-'  . $usuario_id . '-' . $ruta->ID) }}">{{ $ruta->CLAVE}}</a></td>
                                   <td><a href="{{ route('editUsuarioRuta', $empresa_id . '-'  . $usuario_id . '-' . $ruta->ID) }}">{{ $ruta->DESCRIPCION}}</a></td>
                                   <td class="text-center">
                                     <a href="{{ route('anularRuta', $ruta->ID) }}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
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

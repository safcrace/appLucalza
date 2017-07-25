@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading panel-title" style="height: 65px">Usuario por Empresa: <span style="font-weight: 700">{{ $nombreEmpresa->DESCRIPCION }}</span>
                      <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('empresas.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      @can('crear usuario')
                        <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('createUsuario', $empresa) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      @endcan
                  </div>


                  <div class="panel-body">

                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">Código</th>
                         <th class="text-center">Nombre</th>
                         <th class="text-center">Correo Electrónico</th>
                         <th class="text-center">Anular</th>
                         @can('ver rutas')
                           <th class="text-center">Módulo</th>
                         @endcan
                       </thead>
                       <tbody>

                           @foreach ($users as $user)
                               <tr>
                                   <td><a href="{{ route('usuarios.edit', $user->id . '-' . $empresa) }}">{{ $user->id}}</a></td>
                                   <td><a href="{{ route('usuarios.edit', $user->id . '-' . $empresa) }}">{{ $user->nombre}}</a></td>
                                   <td><a href="{{ route('usuarios.edit', $user->id . '-' . $empresa) }}">{{ $user->email}}</a></td>
                                   <td class="text-center">
                                     <a href="{{ route('anularUsuario', $user->id . '-' . $empresa) }}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                                   @can('ver rutas')
                                     <td class="text-center">
                                       <a href="{{ route('indexRutasUsuario', $empresa . '-'  . $user->id) }}"><button type="button" class="btn btn-primary btn-sm">Rutas</button></a>
                                     </td>
                                   @endcan
                               </tr>
                           @endforeach


                       </tbody>


                      </table>

                      <div class="text-center">
                        {!!$users->render()!!}
                      </div>
                  </div>
                  </div>


              </div>
        </div>
  </div>
@endsection

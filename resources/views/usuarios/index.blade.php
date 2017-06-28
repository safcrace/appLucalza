@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading panel-title">Control de Usuarios</div>


                    <div class="panel-body text-right">
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('empresas.index') }}"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('createUsuario', $empresa) }}"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                    </div>


                  <div class="panel-body">

                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">Código</th>
                         <th class="text-center">Nombre</th>
                         <th class="text-center">Correo Electrónico</th>                        
                         <th class="text-center">Anular</th>
                         <th class="text-center">Módulo</th>
                       </thead>
                       <tbody>

                           @foreach ($users as $user)
                               <tr>
                                   <td><a href="{{ route('usuarios.edit', $user->id) }}">{{ $user->id}}</a></td>
                                   <td><a href="{{ route('usuarios.edit', $user->id) }}">{{ $user->nombre}}</a></td>
                                   <td><a href="{{ route('usuarios.edit', $user->id) }}">{{ $user->email}}</a></td>
                                   <td class="text-center">
                                     <a href="{{ route('anular', $user->id) }}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                                   <td class="text-center">
                                     <a href="{{ route('indexRutasUsuario', $empresa . '-'  . $user->id) }}"><button type="button" class="btn btn-primary btn-sm">Rutas</button></a>
                                   </td>
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

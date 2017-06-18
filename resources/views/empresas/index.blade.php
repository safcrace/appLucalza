@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading">Control de Empresas</div>


                    <div class="panel-body text-right">
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('home') }}"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('empresas.create') }}"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                    </div>


                  <div class="panel-body">

                     <table class="table table-bordered table-striped ">
                       <thead>
                         <th class="text-center">Código</th>
                         <th class="text-center">Nombre</th>
                         <th class="text-center">Editar</th>
                         <th class="text-center">Anular</th>
                         <th class="text-center">Módulo</th>
                       </thead>
                       <tbody>

                           @foreach ($empresas as $empresa)
                               <tr>
                                   <td>{{ $empresa->CLAVE}}</td>
                                   <td>{{ $empresa->DESCRIPCION}}</td>
                                   <td class="text-center">
                                     <a href="{{ route('empresas.edit', $empresa->ID) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                                   <td class="text-center">
                                     <a href="{{ route('anularEmpresa', $empresa->ID) }}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                                   <td class="text-center">
                                     <a href="{{ route('indexUsuario', $empresa->ID) }}"><button type="button" class="btn btn-primary btn-sm">Usuarios</button></a>
                                     <a href="{{ route('indexRuta', $empresa->ID) }}"><button type="button" class="btn btn-primary btn-sm">Rutas</button></a>
                                     <a href="{{ route('indexTipoGasto', $empresa->ID) }}"><button type="button" class="btn btn-primary btn-sm">Tipo de Gasto</button></a>
                                     <a href="{{ route('indexProveedor', $empresa->ID) }}"><button type="button" class="btn btn-primary btn-sm">Proveedores</button></a>
                                   </td>
                               </tr>
                           @endforeach


                       </tbody>


                      </table>

                      <div class="text-center">
                        {!!$empresas->render()!!}
                      </div>
                  </div>
                  </div>


              </div>
        </div>
  </div>
@endsection

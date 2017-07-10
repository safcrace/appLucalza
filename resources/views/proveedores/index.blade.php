@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading panel-title">Control de Proveedores</div>


                    <div class="panel-body text-right">
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('empresas.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('createProveedor', $empresa_id) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                    </div>


                  <div class="panel-body">

                     <table class="table table-bordered table-striped table-hover">
                       <thead>
                         <th class="text-center">Identificador Tributario</th>
                         <th class="text-center">Proveedor</th>
                         <th class="text-center">Anular</th>
                       </thead>
                       <tbody>

                           @foreach ($proveedores as $proveedor)
                               <tr>
                                   <td><a href="{{ route('proveedores.edit', $proveedor->ID) }}">{{ $proveedor->IDENTIFICADOR_TRIBUTARIO}}</a></td>
                                   <td><a href="{{ route('proveedores.edit', $proveedor->ID) }}">{{ $proveedor->NOMBRE}}</a></td>
                                   <td class="text-center">
                                     <a href="{{ route('anularProveedor', $proveedor->ID) }}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                               </tr>
                           @endforeach


                       </tbody>


                      </table>

                      <div class="text-center">
                        {!!$proveedores->render()!!}
                      </div>
                  </div>
                  </div>


              </div>
        </div>
  </div>
@endsection

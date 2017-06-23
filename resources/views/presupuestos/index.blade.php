@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading">Control de Presupuesto</div>


                    <div class="panel-body text-right">
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('home') }}"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('presupuestoCreate', $usuario_id) }}"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                    </div>


                  <div class="panel-body">

                     <table class="table table-bordered table-striped ">
                       <thead>
                         <th class="text-center">Código</th>
                         <th class="text-center">Usuario</th>
                         <th class="text-center">Ruta</th>
                         <th class="text-center">Editar</th>
                         <th class="text-center">Anular</th>
                       </thead>
                       <tbody>

                           @foreach ($presupuestos as $presupuesto)
                               <tr>
                                   <td>{{ $presupuesto->ID }}</td>
                                   <td>{{ $presupuesto->USUARIO }}</td>
                                   <td>{{ $presupuesto->RUTA }}</td>
                                   <td class="text-center">
                                     <a href="{{ route('presupuestos.edit', $presupuesto->ID) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                                   <td class="text-center">
                                     <a href="{{-- route('anularEmpresa', $empresa->ID) --}}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                                   <td class="text-center">

                                   </td>
                               </tr>
                           @endforeach


                       </tbody>


                      </table>

                      <div class="text-center">
                        {!!$presupuestos->render()!!}
                      </div>
                  </div>
                  </div>


              </div>
        </div>
  </div>
@endsection

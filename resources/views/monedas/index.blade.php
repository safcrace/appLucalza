@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading">Control de Monedas</div>


                    <div class="panel-body text-right">
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('home') }}"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('monedas.create') }}"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                    </div>


                  <div class="panel-body">

                     <table class="table table-bordered table-striped ">
                       <thead>
                         <th class="text-center">ID</th>
                         <th class="text-center">Clave</th>
                         <th class="text-center">Descripción</th>
                         <th class="text-center">Editar</th>
                         <th class="text-center">Anular</th>
                       </thead>
                       <tbody>

                           @foreach ($monedas as $moneda)
                               <tr>
                                   <td>{{ $moneda->ID}}</td>
                                   <td>{{ $moneda->CLAVE}}</td>
                                   <td>{{ $moneda->DESCRIPCION}}</td>
                                   <td class="text-center">
                                     <a href="{{ route('monedas.edit', $moneda->ID) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                                   <td class="text-center">
                                     <a href="{{ route('anular', $moneda->ID) }}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
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
  </div>
@endsection

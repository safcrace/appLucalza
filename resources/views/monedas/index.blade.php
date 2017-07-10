@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading panel-title">Control de Monedas</div>


                    <div class="panel-body text-right">
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                      <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('monedas.create') }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
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
                               <tr>
                                   <td><a href="{{ route('monedas.edit', $moneda->ID) }}">{{ $moneda->ID}}</a></td>
                                   <td><a href="{{ route('monedas.edit', $moneda->ID) }}">{{ $moneda->CLAVE}}</a></td>
                                   <td><a href="{{ route('monedas.edit', $moneda->ID) }}">{{ $moneda->DESCRIPCION}}</a></td>
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

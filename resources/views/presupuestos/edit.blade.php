@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
            <div class="panel panel-default">
                 <div class="panel-heading">
                    Editar Presupuesto {{ $presupuesto->ID }}

                  </div>
                 <div class="panel-body">

                   {!! Form::model($presupuesto, ['route' => ['presupuestos.update', $presupuesto->ID], 'method' => 'PATCH']) !!}

                     <div class="panel-body text-right">
                       <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('presupuestos.index') }}"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>

                       <button type="submit" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;"></button>
                     </div>
                     @include('presupuestos.partials.fields')
                  {!! Form::close() !!}
                 </div>
               </div>

               <div class="panel panel-default">
                   <div class="panel-heading">Detalle Presupuesto</div>


                     <div class="panel-body text-right">
                       <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('presupuestos.index') }}"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                       <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('createDetalle', $combos->ID) }}"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>
                     </div>


                   <div class="panel-body">

                      <table class="table table-bordered table-striped ">
                        <thead>
                          <th class="text-center">Tipo Gasto</th>
                          <th class="text-center">Frecuencia</th>
                          <th class="text-center">Monto</th>
                          <th class="text-center">Editar</th>
                          <th class="text-center">Anular</th>
                        </thead>
                        <tbody>

                            @foreach ($detallePresupuestos as $detallePresupuesto)
                                <tr>
                                    <td>{{ $detallePresupuesto->TIPOGASTO}}</td>
                                    <td>{{ $detallePresupuesto->MONTO}}</td>
                                    <td>{{ $detallePresupuesto->FRECUENCIA}}</td>
                                    <td class="text-center">
                                      <a href="{{ route('detallePresupuestos.edit', $detallePresupuesto->ID) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                    </td>
                                    <td class="text-center">
                                      <a href="{{ route('anularProveedor', $detallePresupuesto->ID) }}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>


                       </table>

                       <div class="text-center">
                         {!!$detallePresupuestos->render()!!}
                       </div>
                   </div>
                   </div>

              </div>
        </div>
  </div>
@endsection

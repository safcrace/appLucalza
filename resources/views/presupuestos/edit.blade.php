@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
            {!! Form::model($presupuesto, ['route' => ['presupuestos.update', $presupuesto->ID], 'method' => 'PATCH']) !!}
            <div class="panel panel-default">
                 <div class="panel-heading panel-title" style="height: 65px">
                    Editar Presupuesto {{ $presupuesto->ID }}
                     <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('presupuestos.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                     <button type="submit" class="btn btn-default" style="border-color: white; float: right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                  </div>
                 <div class="panel-body">

                     @include('presupuestos.partials.fields')

                  {!! Form::close() !!}
                 </div>
               </div>

               <div class="panel panel-default">
                   <div class="panel-heading panel-title" style="height: 65px">Detalle Presupuesto
                       <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('createDetalle', $presupuesto->ID) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                   </div>

                   <div class="panel-body">

                      <table class="table table-bordered table-striped table-hover">
                        <thead>
                          <th class="text-center">Tipo Gasto</th>
                          <th class="text-center">Frecuencia</th>
                          <th class="text-center">Monto</th>                        
                          <th class="text-center">Anular</th>
                        </thead>
                        <tbody>

                            @foreach ($detallePresupuestos as $detallePresupuesto)
                                <tr>
                                    <td><a href="{{ route('detallePresupuestos.edit', $detallePresupuesto->ID) }}">{{ $detallePresupuesto->TIPOGASTO}}</a></td>
                                    <td><a href="{{ route('detallePresupuestos.edit', $detallePresupuesto->ID) }}">{{ $detallePresupuesto->FRECUENCIA}}</a></td>
                                    <td><a href="{{ route('detallePresupuestos.edit', $detallePresupuesto->ID) }}">{{ $detallePresupuesto->MONTO}}</a></td>
                                    <td class="text-center">
                                      <a href="{{ route('anularDetallePresupuesto', $detallePresupuesto->ID . '-' . $presupuesto->ID) }}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
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

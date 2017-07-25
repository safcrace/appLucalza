@extends('layouts.app')


@include('layouts.headerTwo')
@section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              {!! Form::open(['route' => 'detallePresupuestos.store', 'method' => 'POST']) !!}
              <div class="panel panel-default">
                  <div class="panel-heading panel-title" style="height: 65px">Ingreso Detalle de Presupuesto {{$presupuesto_id}}
                      <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('presupuestos.edit', $presupuesto_id) }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      <button type="submit" class="btn btn-default" style="border-color: white; float: right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" title="Grabar"></button>
                  </div>

                  <div class="panel-body">

                      <div class="panel-body text-right">

                      </div>

                      @include('detallePresupuestos.partials.fields')

                   {!! Form::close() !!}

                  </div>
                  </div>



              </div>
        </div>
  </div>
@endsection

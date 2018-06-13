@extends('layouts.app')


@include('layouts.headerTwo')
@section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              {!! Form::open(['route' => 'facturas.store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'form-save', 'name' => 'form-save']) !!}
              <div class="panel panel-primary">
                  <div class="panel-heading panel-title" style="height: 65px">Ingreso Facturas {{-- de Liquidación {{$liquidacion_id}} --}}
                      <button type="button" class="btn btn-default hidden-xs" style="border-color: white; float: right"><a href="{{ route('liquidaciones.edit', $liquidacion_id . '-' . $tipoLiquidacion) }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      <button type="button" class="btn btn-default btn-xs visible-xs" style="border-color: white; float: right"><a href="{{ route('liquidaciones.edit', $liquidacion_id . '-' . $tipoLiquidacion) }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:24px; color: black"></span></a></button>
                      <button type="button" class="btn btn-default hidden-xs" style="border-color: white; float: right; display: none" id="realizaConversion"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                      <button type="button" class="btn btn-default btn-xs visible-xs" style="border-color: white; float: right; display:none" id="realizaConversionMovil"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:24px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                  </div>

                  <div class="panel-body">

                      @include('facturas.partials.fields')

                   {!! Form::close() !!}

                  </div>
                  </div>



              </div>
        </div>
  </div>
@endsection

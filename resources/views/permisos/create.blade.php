@extends('layouts.app')


@include('layouts.headerTwo')
@section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-default">
                  <div class="panel-heading panel-title">Ingreso de Permisos</div>

                  <div class="panel-body">

                    {!! Form::open(['route' => 'permisos.store', 'method' => 'POST']) !!}


                      <div class="panel-body text-right">
                        <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('permisos.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>

                        <button type="submit" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                      </div>

                      @include('permisos.partials.fields')

                   {!! Form::close() !!}

                  </div>
                  </div>

              </div>
        </div>
  </div>
@endsection

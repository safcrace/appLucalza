@extends('layouts.app')


@include('layouts.headerTwo')
@section('content')
  <div class="container">
      <div class="row">
          {!! Form::open(['route' => ['storeUsuarioRuta',  $empresa_id . '-'  . $usuario_id], 'method' => 'POST']) !!}
          <div class="col-md-12 ">
              <div class="panel panel-primary">
                  <div class="panel-heading panel-title" style="height: 65px">Asignación de {{ $descripcion  }}
                      <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('indexRutasUsuario', $usuario_id . '-' . $descripcion . '-' . $empresa_id)  }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      <button type="submit" class="btn btn-default" style="border-color: white; float: right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;"></button>
                  </div>

                  <div class="panel-body">

                      @include('rutas.partials.fieldsTwo')

                   {!! Form::close() !!}

                  </div>
                  </div>



              </div>
        </div>
  </div>
@endsection

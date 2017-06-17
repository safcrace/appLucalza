@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
            <div class="panel panel-default">
                 <div class="panel-heading">
                    Editar Tasa Cambio {{ $tasaCambio->FECHA }}

                  </div>
                 <div class="panel-body">

                   {!! Form::model($tasaCambio, ['route' => ['tasaCambio.update', $tasaCambio->ID], 'method' => 'PATCH']) !!}

                     <div class="panel-body text-right">
                       <button type="button" class="btn btn-default" style="border-color: white"><a href="{{ route('monedas.index') }}"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a>

                       <button type="submit" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;"></button>
                     </div>

                     @include('monedas.partials.fieldsTwo')

                  {!! Form::close() !!}
                 </div>
               </div>


              </div>
        </div>
  </div>
@endsection
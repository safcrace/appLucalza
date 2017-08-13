@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
            {!! Form::model($empresa, ['route' => ['empresas.update', $empresa->ID], 'method' => 'PATCH']) !!}
            <div class="panel panel-primary">
                 <div class="panel-heading panel-title" style="height: 65px">Editar Empresa {{ $empresa->DESCRIPCION }}
                     <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('empresas.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                     <button type="submit" class="btn btn-default" style="border-color: white; float: right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                 </div>
                 <div class="panel-body">

                     <div class="panel-body text-right">

                     </div>
                     @include('empresas.partials.fields')

                  {!! Form::close() !!}
                 </div>
               </div>


              </div>
        </div>
  </div>
@endsection

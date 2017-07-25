@extends('layouts.app')

@include('layouts.headerOne')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                {{--@include('partials/success')--}}
                <div class="panel panel-default" style="margin-top: 200px">
                    <div class="panel-heading">Bienvenido!</div>
                    <div class="panel-body">
                        <h1>Lucalza</h1>
                        <p>Bienvenido a Nuestra Aplicación</p>

                        <p class="text-center">Aqui irá el logo de la Empresa: <span style="font-weight: 700">{{$empresa->DESCRIPCION}}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@include('layouts.headerOne')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                {{--@include('partials/success')--}}
                <div class="panel panel-primary" style="margin-top: 200px">
                    <div class="panel-heading main-title">Bienvenido!</div>
                    <div class="panel-body">
                        <h1>Lucalza</h1>
                        <p>Bienvenido a Nuestra Aplicación</p>


                        <div class="text-center"><img src="{{ asset('images/logoLucalza.PNG') }}" alt=""></div>

                        <p class="text-center"><span style="font-weight: 700">{{$empresa->DESCRIPCION}}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

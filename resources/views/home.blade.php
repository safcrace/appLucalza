@extends('layouts.app')

@include('layouts.headerOne')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                {{--@include('partials/success')--}}
                <div class="panel panel-default">
                    <div class="panel-heading">Bienvenido!</div>
                    <div class="panel-body">
                        <h1>Lucalza</h1>
                        <p>Bienvenido a Nuestro Sitio</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

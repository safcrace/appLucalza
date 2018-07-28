<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" >
    {{--<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">--}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lucalza') }}</title>

    <!-- Styles -->

    <!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.css') }}">

</head>
<body>
    <div id="app">

        <div class="" style="background-color: #2F333B" widht="350px">

        </div>

        <div class="container">
            @include('partials.messages')
            @if(Session::has('info'))
                <div class="alert alert-danger" role="alert">
                    <b>{{ Session('info') }}</b>
                </div>
            @endif
        </div>


        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>-->
    <script src="{{ asset('js/bootstrap.min.js')}}" charset="utf-8"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js')}}" charset="utf-8"></script>
    <script src="{{ asset('js/select2.js')}}" charset="utf-8"></script>
    @stack('scripts')
</body>
</html>

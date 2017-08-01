@extends('layouts.app')


@include('layouts.headerTwo')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                {!! Form::open(['route' => 'storeUsuarioEmpresa', 'method' => 'POST']) !!}
                <div class="panel panel-default">
                    <div class="panel-heading panel-title" style="height: 65px">Asignación de Usuarios a Empresas
                        <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('empresas.index') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                        <button type="submit" class="btn btn-default" style="border-color: white; float: right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                    </div>

                    <div class="panel-body">

                        @include('usuariosXempresa.partials.fields')

                        {!! Form::close() !!}

                    </div>
                </div>

                {{-- Usuario Asignados --}}

                <div id="empresas">

                </div>



            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var usuario = $('#usuario').val();
         if (usuario != "") {
             vurl = '{{ route('usuariosEmpresa')}}'
             vurl = vurl.replace('%7Bid%7D', usuario);

             $.ajax({
             type: 'get',
             url: vurl,
             success: function (data) {
                $('#empresas').empty().html(data);
             }
             });
         }


        $('#usuario').change(function () {
            var usuario = $('#usuario').val();
            vurl = '{{ route('usuariosEmpresa')}}'
            vurl = vurl.replace('%7Bid%7D', usuario);

            $.ajax({
                type: 'get',
                url: vurl,
                success: function (data) {
                    $('#empresas').empty().html(data);
                }
            });
        });

        $('#usuario').select2({
            placeholder: 'Seleccione un Usuario'
        });

    });
</script>
@endpush
@extends('layouts.app')


@include('layouts.headerTwo')
@section('content')
    <div class="container">
        @if(Session::has('validaUsuarioEmpresa'))
            <div class="alert alert-danger" role="alert">
                {{ Session::get('validaUsuarioEmpresa') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12 ">
                {!! Form::open(['route' => 'storeUsuarioEmpresa', 'method' => 'POST']) !!}
                <div class="panel panel-primary">
                    <div class="panel-heading panel-title" style="height: 65px">Asignación de Usuarios a Empresas
                        <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                        <button type="submit" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('home') }}" title="Grabar"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;"></a></button>
                    </div>

                    <div class="panel-body">

                        @include('usuariosXempresa.partials.fields')

                        {!! Form::close() !!}

                    </div>

                    <div id="empresas">

                    </div>
                </div>

                {{-- Usuario Asignados --}}





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
                 $('#agregar').show()
                $('#empresas').empty().html(data);
             }
             });
         }

        $('#usuario').select2({
            placeholder: 'Seleccione un Proveedor'
        })

        /*$('#empresa').select2({
            placeholder: 'Seleccione un una empresa'
        })*/

         $('#imagen').click(function() {
             alert('Hola Sender');
        });


        $('#usuario').change(function () {
            var usuario = $('#usuario').val();
            vurl = '{{ route('usuariosEmpresa')}}'
            vurl = vurl.replace('%7Bid%7D', usuario);

            $.ajax({
                type: 'get',
                url: vurl,
                success: function (data) {
                    $('#agregar').show()
                    $('#empresas').empty().html(data);
                }
            });
        });

        $('#usuario').select2({
            placeholder: 'Seleccione un Usuario'
        });



        $('#empresa').change(function() {
            var criterio = $('#empresa').val() + '-' + 2            
            vurl = '{{ route('codigoProveedorSap')}}'
            vurl = vurl.replace('%7Bid%7D', criterio);
            $.ajax({
                type: 'get',
                url: vurl,
                success: function (data) {
                    $('#pro_sap').hide()
                    $('#pro_sap').empty().html(data);
                    $('#pro_sap').show()
                    $('#cod_pro').remove()
                    $('#codigoProveedorSap').select2({
                        placeholder: 'Seleccione un Usuario'
                    });
                }
            });

            $('#pro_sap').on('change', '#codigoProveedorSap', function() {   
                             
                var descripcion_proveedorSap = $('#codigoProveedorSap option:selected').text()
                $('#descripcion_proveedorsap').val(descripcion_proveedorSap)
            }) 
            
            var criterio2 = $('#empresa').val() + '-' +  $('#usuario option:selected').text()            
                        
            vurl = '{{ route('codigoUsuario')}}'
            vurl = vurl.replace('%7Bid%7D', criterio2);          
            $.ajax({
                type: 'get',
                url: vurl,
                success: function (data2) {
                    $('#selectCodigo').hide()
                    $('#selectCodigo').empty().html(data2);
                    $('#selectCodigo').show()
                    $('#textoCodigo').remove()
                    $('#usersap_id').select2({
                        placeholder: 'Seleccione un Usuario'
                    });
                }
            });

            $('#selectCodigo').on('change', '#usersap_id', function() {   
                             
                var descripcion_codigoUsuario = $('#usersap_id option:selected').text()
                $('#descripcion_codigousuario').val(descripcion_codigoUsuario)
            }) 


        });

        

        $('#proveedorSap').click(function() {
            var criterio = $('#empresa').val() + '-' + 2            
            vurl = '{{ route('codigoProveedorSap')}}'
            vurl = vurl.replace('%7Bid%7D', criterio);
            $.ajax({
                type: 'get',
                url: vurl,
                success: function (data) {
                    $('#pro_sap').empty().html(data);
                    $('#pro_sap').show()
                    $('#cod_pro').remove()
                }
            });

            $('#pro_sap').on('change', '#codigoProveedorSap', function() {                
                var descripcion_proveedorSap = $('#codigoProveedorSap option:selected').text()
                $('#descripcion_proveedorsap').val(descripcion_proveedorSap)
            })            
        })

        $('#codigoSap').click(function() {            
            var criterio = $('#empresa').val() + '-' +  $('#usuario option:selected').text()            
                        
            vurl = '{{ route('codigoUsuario')}}'
            vurl = vurl.replace('%7Bid%7D', criterio);          
            $.ajax({
                type: 'get',
                url: vurl,
                success: function (data) {
                    $('#selectCodigo').empty().html(data);
                    $('#selectCodigo').show()
                    $('#textoCodigo').remove()
                }
            });
        })

        $('#botonAgregar').click(function() {            
            $('#agregarEmpresa').show()
        })


    });
</script>
@endpush
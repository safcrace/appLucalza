
@if (isset($liquidacion_id))
    <input id="LIQUIDACION_ID" name="LIQUIDACION_ID" type="hidden" value="{{ $liquidacion_id }}">
@endif

{!! Form::hidden('PRESUPUESTO_ID', $presupuesto->ID) !!}
{!! Form::hidden('TIPO_LIQUIDACION', $tipoLiquidacion) !!}
{!! Form::hidden('TASA_CAMBIO', null, ['id' => 'tasaCambio']) !!}
{!! Form::hidden('MONEDA_ID', $monedaEmpresa->LOCAL, ['id' => 'idMoneda']) !!}
{!! Form::hidden('CATEGORIA_GASTO', 'Parametro', ['id' => 'categoriaGasto']) !!}
{!! Form::hidden('SUBCATEGORIA_GASTO', (isset($factura->SUBCATEGORIA_TIPOGASTO_ID))?$factura->SUBCATEGORIA_TIPOGASTO_ID:null, ['id' => 'subCategoriaGasto']) !!}
@if (isset($factura))
    {!! Form::hidden('URL_IMAGEN_FACTURA', $factura->EMAIL . '/' . $factura->FOTO, ['id' => 'urlImagenFactura']) !!}
    {!! Form::hidden('ID_FACTURA', $factura->ID, ['id' => 'idFactura']) !!}
@endif

<div class="panel panel-primary">
  <div class="panel-heading">Datos de la Factura </div>
  <div class="panel-body">
    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('TIPOGASTO_ID', 'Categoría Gasto') !!}
      </div>
      <div class="col-md-3">
          {!! Form::select('TIPOGASTO_ID', $tipoGasto, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Categoria de Gasto', 'id' => 'tipoGasto']); !!}
      </div>

      <div class="col-md-2">
            {!! Form::label('subcategoriaTipoGasto', 'Tipo de Gasto') !!}
        </div>
        @if(isset($factura->SUBCATEGORIA_TIPOGASTO_ID))
            <div class="col-md-3">                
                {!! Form::select('subcategoriaTipoGasto', $subcategoria, $factura->SUBCATEGORIA_TIPOGASTO_ID, ['class' => 'form-control', 'placeholder' => 'Seleccione Tipo de Gasto', 'id' => 'subcategoriaTipoGasto']); !!}
            </div>
        @else
            <div class="col-md-3">
                {!! Form::select('subcategoriaTipoGasto', ['00' => 'SELECCIONE CATEGORIA DE GASTO'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione Tipo de Gasto', 'id' => 'subcategoriaTipoGasto']); !!}
            </div>
        @endif

      
    </div>

    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
                {!! Form::label('PROVEEDOR_ID', 'NIT Proveedor') !!}  <a href="#" title="Agregar Proveedor" data-toggle="modal" data-target="#myModalA"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:18px; color: black; padding-left:35px"></span></a>
            </div>
            <div class="col-md-3" id="proveedorTemporal" style="display: block">
                {!! Form::select('PROVEEDOR_ID', $proveedor, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Proveedor', 'id' => 'nit']); !!} 
            </div>    
            

      <div class="col-md-2">
            {!! Form::label('NOMBRE_PROVEEDOR', 'Nombre Proveedor') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('NOMBRE_PROVEEDOR', 'Nombre Proveedor', ['class' => 'form-control', 'disabled' => 'true', 'id' => 'nombreProveedor']); !!}
      </div>

    </div>


    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('FMONEDA_ID', 'Moneda') !!}
        </div>
        {{--  <div class="col-md-2">
            {!! Form::radio('FMONEDA_ID', $moneda->ID, true); !!}  {{ $moneda->DESCRIPCION }}
        </div>
        <div class="col-md-1">
            {!! Form::radio('FMONEDA_ID', 2); !!}  Dólar
        </div>  --}}
        @if(isset($factura->MONEDA_ID))
            @if(trim($factura->MONEDA_ID) == 'QTZ')                
                <div class="col-md-1 ">
                    {!! Form::radio('FMONEDA_ID', $monedaEmpresa->MONEDA_LOCAL, true); !!}  {{ $monedaEmpresa->MONEDA_LOCAL }}
                </div>
                <div class="col-md-2">
                    {!! Form::radio('FMONEDA_ID', $monedaEmpresa->MONEDA_SYS, false); !!}  {{ $monedaEmpresa->MONEDA_SYS }}
                </div>
            @else 
            {{ $factura->MONEDA_ID }}
            <div class="col-md-1 ">
                    {!! Form::radio('FMONEDA_ID', $monedaEmpresa->MONEDA_LOCAL, false); !!}  {{ $monedaEmpresa->MONEDA_LOCAL }}
                </div>
                <div class="col-md-2">
                    {!! Form::radio('FMONEDA_ID', $monedaEmpresa->MONEDA_SYS, true); !!}  {{ $monedaEmpresa->MONEDA_SYS }}
                </div>
            @endif
        @else
            <div class="col-md-1 ">
                {!! Form::radio('FMONEDA_ID', $monedaEmpresa->MONEDA_LOCAL, true); !!}  {{ $monedaEmpresa->MONEDA_LOCAL }}
            </div>
            <div class="col-md-2">
                {!! Form::radio('FMONEDA_ID', $monedaEmpresa->MONEDA_SYS, false); !!}  {{ $monedaEmpresa->MONEDA_SYS }}
            </div>
        @endif

        <div class="col-md-2">
            {!! Form::label('TIPODOCUMENTO_ID', 'Tipo de Documento') !!}
        </div>
        <div class="col-md-3" id="proveedorTemporal" style="display: block">
            {!! Form::select('TIPODOCUMENTO_ID', $tipoDocumento, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Tipo de Documento', 'id' => 'tipoDocumento']); !!}
        </div>            
    </div>

    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('FECHA_FACTURA', 'Fecha') !!}
        </div>
        <div class="col-md-2">
            @if($fechaFactura)
                {!! Form::date('FECHA_FACTURA', $fechaFactura, ['class' => 'form-control']); !!}
            @else
                {!! Form::date('FECHA_FACTURA', null, ['class' => 'form-control']); !!}
            @endif
        </div>
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('SERIE', 'Serie') !!}
        </div>
        <div class="col-md-3">
            {!! Form::text('SERIE', null, ['class' => 'form-control', 'placeholder' => 'Serie', 'id' => 'numSerie']); !!}
        </div>
    </div>
    
    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('NUMERO', 'Número') !!}
        </div>
        <div class="col-md-3">
            {!! Form::text('NUMERO', null, ['class' => 'form-control', 'placeholder' => 'Número', 'id' => 'numero']); !!}
        </div>        
        <div class="col-md-2 ">
            {!! Form::label('TOTAL', 'Total') !!}
        </div>
        <div class="col-md-3">
            {!! Form::text('TOTAL', null, ['class' => 'form-control', 'placeholder' => 'Total']); !!}
        </div>
    </div>
    
    <div class="row form-group combus" style="display: none">
        <hr>
            <h4 style="text-align: center">Control de Combustible</h4>
            <br>
            <div class="col-md-2 col-md-offset-1 combus" style="display: none">
                    <div id="etiqueta" style="font-weight: 700">Cantidad Galones</div>
                </div>
                <div class="col-md-2 combus" style="display: none">
                    @if(isset($factura->CANTIDAD_PORCENTAJE_CUSTOM))
                        {!! Form::text('CANTIDAD_PORCENTAJE_CUSTOM', $factura->CANTIDAD_PORCENTAJE_CUSTOM, ['class' => 'form-control', 'placeholder' => 'Cantidad']); !!}
                    @else
                        {!! Form::text('CANTIDAD_PORCENTAJE_CUSTOM', null, ['class' => 'form-control', 'placeholder' => 'Cantidad']); !!}
                    @endif
                </div>
                <div class="col-md-1 combus" style="display: none">
                    {!! Form::label('KM_INICIO', 'Kilometraje Inicial') !!}
                </div>
                <div class="col-md-2 combus" style="display: none">
                    @if(isset($factura->KILOMETRAJE_INICIAL))
                        {!! Form::text('KM_INICIO', $factura->KILOMETRAJE_INICIAL, ['class' => 'form-control', 'placeholder' => 'Inicio']); !!}
                    @else
                        {!! Form::text('KM_INICIO', null, ['class' => 'form-control', 'placeholder' => 'Inicio']); !!}
                    @endif
                </div>
                <div class="col-md-1 combus" style="display: none">
                        {!! Form::label('KM_FINAL', 'Kilometraje Final') !!}
                </div>
                <div class="col-md-2 combus" style="display: none">
                    @if(isset($factura->KILOMETRAJE_FINAL))
                        {!! Form::text('KM_FINAL', $factura->KILOMETRAJE_FINAL, ['class' => 'form-control', 'placeholder' => 'Final']); !!}
                    @else
                        {!! Form::text('KM_FINAL', null, ['class' => 'form-control', 'placeholder' => 'Final']); !!}
                    @endif
                </div>
    </div>   

    <hr>

    <div class="row form-group">
        <div class="col-md-1 col-md-offset-1">
            {!! Form::label('COMENTARIO_PAGO', 'Comentario') !!}
        </div>
        <div class="col-md-4">
            {!! Form::textarea('COMENTARIO_PAGO', null, ['class' => 'form-control', 'rows' => '3', 'cols' => '500', 'placeholder' => 'Observaciones y/o Comentarios']); !!}
        </div>
        <div class="col-md-2 ">
                {!! Form::label('FOTO', 'Imagen Factura') !!}
            </div>
        
            @if(isset($factura->EMAIL))
                <div class="col-md-1 text-center">
                    <a class="image-popup-fit-width" href='{{ asset("images/$factura->EMAIL/$factura->FOTO") }}' title="Imagen Factura.">
                        <img src='{{ asset("images/$factura->EMAIL/$factura->FOTO") }}' height="32px">
                    </a>
                </div>
                <div class="col-md-2 col-md-offset-1">
                    <a href="#" title="Reemplazar Factura" data-toggle="modal" data-target="#myModalS"><span class="glyphicon glyphicon-refresh" aria-hidden="true" style="font-size:24px; color: black"></span></a>
                </div>
            @else
                <div class="col-md-2">
                    {!! Form::file('FOTO', ['id' => 'validaTiempo']); !!}
                </div>
            @endif
    </div>


      @if(isset($factura->COMENTARIO_SUPERVISOR))
          <div class="row form-group">
              <div class="col-md-1 col-md-offset-1">
                  {!! Form::label('COMENTARIO_SUPERVISOR', 'Rechazo Supervisor') !!}
              </div>
              <div class="col-md-8">
                  {!! Form::textarea('COMENTARIO_SUPERVISOR', null, ['class' => 'form-control', 'rows' => '3', 'cols' => '500', 'placeholder' => 'Observaciones y/o Comentarios', 'disabled']); !!}
              </div>
          </div>
      @endif

      @if(isset($factura->COMENTARIO_CONTABILIDAD))
          <div class="row form-group">
              <div class="col-md-1 col-md-offset-1">
                  {!! Form::label('COMENTARIO_CONTABILIDAD', 'Rechazo Contabilidad') !!}
              </div>
              <div class="col-md-8">
                  {!! Form::textarea('COMENTARIO_CONTABILIDAD', null, ['class' => 'form-control', 'rows' => '3', 'cols' => '500', 'placeholder' => 'Observaciones y/o Comentarios', 'disabled']); !!}
              </div>
          </div>
      @endif
    {{--<div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('ANULADODP', 'Anular') !!}
      </div>
      <div class="col-md-4">
          {!! Form::checkbox('ANULADOPD', 1); !!}
      </div>
    </div>--}}

  </div>
</div>




<div class="modal fade" id="myModalA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Ingreso de Proveedor</h4>
            </div>

            <div class="modal-body">

                    @include('proveedores.partials.fields')

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>
                <button type="button" class="btn btn-default" style="border-color: white" id="grabarProveedor"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;"></button>
            </div>
        </div>
    </div>
</div>




@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {

        var urlImagen = $('#urlImagenFactura').val(),
            idFactura = $('#idFactura').val()
        $('#facturaUrl').val(urlImagen)
        $('#id_factura').val(idFactura)

        $.fn.populateSelect = function (values) {
            var options = ''
            $.each(values, function (key, row) {
                options += '<option value = "' + row.ID + '">' + row.DESCRIPCION + '</option>'
            })
            $(this).html(options)
        }
        /*
        var tipo = $('#tipoGasto').val();
        if (tipo != null) {
            //alert('Bien!!')
            vurl = '{{ route('tipoGasto') }}'
            vurl = vurl.replace('%7Bid%7D', tipo);

            $.ajax({
                type:'get',
                url:vurl,
                success: function(data){
                    if(data == 'Galones') {
                        $('#etiqueta').html(data);
                        $('#cantidadPorcentaje').show();
                    }
                }
            });
        }*/
        var tipo = $('#tipoGasto').val()
        if (tipo != null) {
            //alert($factura->SUBCATEGORIA_TIPOGASTO_ID)
            var categoria = $('#tipoGasto option:selected').text()
            var $existeFactura = $('#idFactura').val()
            
            vurl = '{{ route('getSubcategoriaTipoGasto') }}'
            vurl = vurl.replace('%7Bid%7D', tipo);
                
                if ($existeFactura == null) {
                    $.getJSON(vurl, null, function (values) {
                        $('#subcategoriaTipoGasto').populateSelect(values)
                    })                    
                }     

            vurl = '{{ route('getTipoGrupo') }}'
            vurl = vurl.replace('%7Bid%7D', tipo); 

            $.ajax({
                type: 'get',
                url: vurl,                    
                success: function (data) {
                    if (data == 'BC') {
                        $('.combus').show()
                    } else {
                        $('.combus').hide() 
                    }                        
                }
            })

            
        }
        
        

        $('#tipoGasto').change(function () {
            var tipo = $('#tipoGasto').val();
            //var categoria = $('#tipoGasto option:selected').text()
           
            if (tipo == '') {                
                $('#subcategoriaTipoGasto').empty()
            } else {
                vurl = '{{ route('getSubcategoriaTipoGasto') }}'
                vurl = vurl.replace('%7Bid%7D', tipo);
                
                $.getJSON(vurl, null, function (values) {
                    $('#subcategoriaTipoGasto').populateSelect(values)
                })
            }


            vurl = '{{ route('getTipoGrupo') }}'
            vurl = vurl.replace('%7Bid%7D', tipo); 

            $.ajax({
                type: 'get',
                url: vurl,                    
                success: function (data) {
                    if (data == 'BC') {
                        $('.combus').show()
                    } else {
                        $('.combus').hide() 
                    }                        
                }
            })

            /* if (categoria == 'Combustible') {
                $('#categoriaGasto').val('combustible')
                $('.combus').show()
            } else if (categoria == 'Depreciación') {
                $('#categoriaGasto').val('depreciación')
                $('.combus').show()
            } else {
                $('#categoriaGasto').val('otros')
                $('.combus').hide()                
            }             */

        });
        
        $('#subcategoriaTipoGasto').change(function() {
            var subcategoria = $('#subcategoriaTipoGasto option:selected').text()
            $('#subCategoriaGasto').val(subcategoria)           
        })

        
        
        $("input[name=FMONEDA_ID]").click(function () {                           
            tipoMoneda = $(this).val()
            $('#idMoneda').val(tipoMoneda)
            //alert(tipoMoneda)           
            //$("#MLOCAL").attr('checked', false);
        });    
        

        

        var tipoDocumento = $('#tipoDocumento option:selected').text()
        if(tipoDocumento.toLowerCase() == 'recibo') {
            $('.numero').hide()
        }

        $('#tipoDocumento').change(function() {
            
            var tipoDocumento = $('#tipoDocumento option:selected').text()
            var patron = 'factura'
            //tipoDocumento = tipoDocumento.toLowerCase()
            if(tipoDocumento.toLowerCase().indexOf('factura') != -1) {                   
                $('.numero').show()
            } else {                        
                $('#numSerie').val('')
                $('.numero').hide()                
            }
            //alert(tipoDocumento)
        })

        $('#realizaConversion').click(function() { 
            $('#realizaConversion').hide();
            var idMoneda = $("#idMoneda").val()
            idMoneda = idMoneda.trim()
           
            if(idMoneda == 'USD') {

                var fechaFactura = $("#FECHA_FACTURA").val()
                vurl = '{{ route('tasaCambio')}}'            
                if (fechaFactura === '') {
                    alert('Debe ingresar una fecha!')
                }                           
                vurl = vurl.replace('%7Bid%7D', fechaFactura);              
                $.ajax({
                    type: 'get',
                    url: vurl,                
                    success: function (data) {                        
                        $('#tasaCambio').val(data); 
                        $('#form-save').submit()                     
                    }
                });                                
            } else {                
                $('#tasaCambio').val(1.00)    
                $('#form-save').submit()
            }
            
        })

        $('#realizaConversionMovil').click(function() { 
            var idMoneda = $("#idMoneda").val()
            idMoneda = idMoneda.trim()
           
            if(idMoneda == 'USD') {

                var fechaFactura = $("#FECHA_FACTURA").val()
                vurl = '{{ route('tasaCambio')}}'            
                if (fechaFactura === '') {
                    alert('Debe ingresar una fecha!')
                }                           
                vurl = vurl.replace('%7Bid%7D', fechaFactura);              
                $.ajax({
                    type: 'get',
                    url: vurl,                
                    success: function (data) {                        
                        $('#tasaCambio').val(data); 
                        $('#form-save').submit()                     
                    }
                });                                
            } else {                
                $('#tasaCambio').val(1.00)    
                $('#form-save').submit()
            }
            
        })

        var nit = $('#nit').val();
        if (nit != null) {
            vurl = '{{ route('facturas.show')}}'

            vurl = vurl.replace('%7Bfacturas%7D', nit);

            $.ajax({
                type:'get',
                url:vurl,
                success: function(data){
                    $('#nombreProveedor').val(data);
                }
            });
        }

        $('#nit').change(function () {

            var nit = $('#nit').val();
            vurl = '{{ route('facturas.show')}}'

            vurl = vurl.replace('%7Bfacturas%7D', nit);

            $.ajax({
                type:'get',
                url:vurl,
                success: function(data){
                    $('#nombreProveedor').val(data);
                }
            });
        });

        $('#nit').select2({
            placeholder: 'Seleccione un Número de identificación tributaria'
        });

        $('#tipoDocumento').select2({
            placeholder: 'Seleccione un Número de identificación tributaria'
        });

        $('#grabarProveedor').click(function () {
            var nombre = $('#nombre').val()
            var identificadorTributario = $('#identificador_tributario').val()
            var domicilio = $('#domicilio').val()
            if (nombre && identificadorTributario && domicilio) {
                alert(nombre)
            } else {
                alert('Los Campos Identificardor Tributario, Nombre y Dirección, son Obligatorios!  ')
            } 
            
            var form = $('#form-save')
            var url = '{{ route('proveedores.store') }}'
            var data = form.serialize()
            

            $.post(url, data, function (data) {
                alert(data)
                $('#nit').html("<option value='" + data[2] + "'>" + data[0] + "</option>")
                //$('#nit').html(data[0])
                $('#proveedorNuevo').val(data[2])
                $('#nombreProveedor').val(data[1])
                $('#myModalA').modal('hide')
            })
        });

        $('#sustituirImagen').click(function () {
            var form = $('#form-update')
            var url = '{{ route('sustituirFactura') }}'
            var data = form.serialize()
//alert(data)/*
            $.post(url, data, function (data) {               
                 alert(data)
                //$('#nit').html("<option value='" + data[2] + "'>" + data[0] + "</option>")
                //$('#nit').html(data[0])
                //$('#proveedorNuevo').val(data[2])
                //$('#nombreProveedor').val(data[1])
                //$('#myModalA').modal('hide')
            })
        });

        $('#validaTiempo').click(function() {
            $('#realizaConversion').hide()
            $('#realizaConversionMovil').hide()
        })

        $('#validaTiempo').change(function () {
            $('#realizaConversion').show()
            $('#realizaConversionMovil').show()
            /*setTimeout(function(){
               alert('Verifique que su Archivo haya subido!')
            }, 3000);*/
        })

        $('.popup-gallery').magnificPopup({
            delegate: 'a',
            type: 'image',
            tLoading: 'Loading image #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0,1] // Will preload 0 - before current, and 1 after the current image
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                titleSrc: function(item) {
                    return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
                }
            }
        });
        $('.image-popup-vertical-fit').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            mainClass: 'mfp-img-mobile',
            image: {
                verticalFit: true
            }
        });
        $('.image-popup-fit-width').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            image: {
                verticalFit: false
            }
        });
        $('.image-popup-no-margins').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            closeBtnInside: false,
            fixedContentPos: true,
            mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
            image: {
                verticalFit: true
            },
            zoom: {
                enabled: true,
                duration: 300 // don't foget to change the duration also in CSS
            }
        });

    });
</script>
@endpush

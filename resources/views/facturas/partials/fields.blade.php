
@if (isset($liquidacion_id))
    <input id="LIQUIDACION_ID" name="LIQUIDACION_ID" type="hidden" value="{{ $liquidacion_id }}">
@endif

{!! Form::hidden('PRESUPUESTO_ID', $presupuesto->ID) !!}
{!! Form::hidden('TIPO_LIQUIDACION', $tipoLiquidacion) !!}
{!! Form::hidden('CATEGORIA_GASTO', 'Parametro', ['id' => 'categoriaGasto']) !!}
{!! Form::hidden('SUBCATEGORIA_GASTO', 'Parametro', ['id' => 'subCategoriaGasto']) !!}
{!! Form::hidden('URL_IMAGEN_FACTURA', $factura->EMAIL . '/' . $factura->FOTO, ['id' => 'urlImagenFactura']) !!}
{!! Form::hidden('ID_FACTURA', $factura->ID, ['id' => 'idFactura']) !!}

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
            {!! Form::label('PROVEEDOR_ID', 'NIT Proveedor') !!}
      </div>
      <div class="col-md-3" id="proveedorTemporal" style="display: block">
          {!! Form::select('PROVEEDOR_ID', $proveedor, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Proveedor', 'id' => 'nit']); !!}
      </div>

      <div class="col-md-1">
          <a href="#" title="Agregar Proveedor" data-toggle="modal" data-target="#myModalA"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:24px; color: black"></span></a>
      </div>
    </div>

    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('SUBCATEGORIATIPOGASTO_ID', 'Tipo de Gasto') !!}
        </div>
        <div class="col-md-3">
            {!! Form::select('SUBCATEGORIATIPOGASTO_ID', $subcategoria, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Tipo de Gasto', 'id' => 'subcategoriaTipoGasto']); !!}
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
        <div class="col-md-2">
            {!! Form::radio('FMONEDA_ID', $moneda->ID, true); !!}  {{ $moneda->DESCRIPCION }}
        </div>
        <div class="col-md-1">
            {!! Form::radio('FMONEDA_ID', 2); !!}  Dólar
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
            @else
                <div class="col-md-2">
                    {!! Form::file('FOTO'); !!}
                </div>
            @endif

            <div class="col-md-2 col-md-offset-1">
                <a href="#" title="Reemplazar Factura" data-toggle="modal" data-target="#myModalS"><span class="glyphicon glyphicon-refresh" aria-hidden="true" style="font-size:24px; color: black"></span></a>
            </div>
    </div>

    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
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
    </div>

    <div class="row form-group numero">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('SERIE', 'Serie') !!}
        </div>
        <div class="col-md-3">
            {!! Form::text('SERIE', null, ['class' => 'form-control', 'placeholder' => 'Serie']); !!}
        </div>

    </div>

    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('NUMERO', 'Número') !!}
        </div>
        <div class="col-md-3">
            {!! Form::text('NUMERO', null, ['class' => 'form-control', 'placeholder' => 'Número']); !!}
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('TOTAL', 'Total') !!}
        </div>
        <div class="col-md-2">
            {!! Form::text('TOTAL', null, ['class' => 'form-control', 'placeholder' => 'Total']); !!}
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
        <div class="col-md-1 combus" style="display: none">
            <div id="etiqueta" style="font-weight: 700">Cantidad Galones</div>
        </div>
        <div class="col-md-1 combus" style="display: none">
            @if(isset($factura->CANTIDAD_PORCENTAJE_CUSTOM))
                {!! Form::text('CANTIDAD_PORCENTAJE_CUSTOM', $factura->CANTIDAD_PORCENTAJE_CUSTOM, ['class' => 'form-control', 'placeholder' => 'Cantidad']); !!}
            @else
                {!! Form::text('CANTIDAD_PORCENTAJE_CUSTOM', null, ['class' => 'form-control', 'placeholder' => 'Cantidad']); !!}
            @endif
        </div>
        <div class="col-md-1 combus" style="display: none">
            {!! Form::label('KM_INICIO', 'Km Inicio') !!}
        </div>
        <div class="col-md-1 combus" style="display: none">
            @if(isset($factura->KILOMETRAJE_INICIAL))
                {!! Form::text('KM_INICIO', $factura->KILOMETRAJE_INICIAL, ['class' => 'form-control', 'placeholder' => 'Inicio']); !!}
            @else
                {!! Form::text('KM_INICIO', null, ['class' => 'form-control', 'placeholder' => 'Inicio']); !!}
            @endif
        </div>
        <div class="col-md-1 combus" style="display: none">
                {!! Form::label('KM_FINAL', 'Km Final') !!}
        </div>
        <div class="col-md-1 combus" style="display: none">
            @if(isset($factura->KILOMETRAJE_FINAL))
                {!! Form::text('KM_FINAL', $factura->KILOMETRAJE_FINAL, ['class' => 'form-control', 'placeholder' => 'Final']); !!}
            @else
                {!! Form::text('KM_FINAL', null, ['class' => 'form-control', 'placeholder' => 'Final']); !!}
            @endif
        </div>
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
            var categoria = $('#tipoGasto option:selected').text()
            vurl = '{{ route('getSubcategoriaTipoGasto') }}'
            vurl = vurl.replace('%7Bid%7D', tipo);
                
            $.getJSON(vurl, null, function (values) {
                $('#subcategoriaTipoGasto').populateSelect(values)
            })

            if (categoria == 'Combustible') {
                $('#categoriaGasto').val('combustible')
                $('.combus').show()
            } else if (categoria == 'Depreciación') {
                $('#categoriaGasto').val('depreciación')
                $('.combus').show()
            } else {
                $('.combus').hide()
            }
        }


        $('#tipoGasto').change(function () {
            var tipo = $('#tipoGasto').val();
            var categoria = $('#tipoGasto option:selected').text()
            /*vurl = '{{ route('tipoGasto') }}'
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
            });*/

            if (tipo == '') {

                $('#subcategoriaTipoGasto').empty()
            } else {
                vurl = '{{ route('getSubcategoriaTipoGasto') }}'
                vurl = vurl.replace('%7Bid%7D', tipo);
                
                $.getJSON(vurl, null, function (values) {
                    $('#subcategoriaTipoGasto').populateSelect(values)
                })
            }

            if (categoria == 'Combustible') {
                $('#categoriaGasto').val('combustible')
                $('.combus').show()
            } else if (categoria == 'Depreciación') {
                $('#categoriaGasto').val('depreciación')
                $('.combus').show()
            } else {
                $('.combus').hide()
            }

        });

        $('#tipoDocumento').change(function() {
            var subcategoria = $('#subcategoriaTipoGasto option:selected').text()
            $('#subCategoriaGasto').val(subcategoria)
            var tipoDocumento = $('#tipoDocumento option:selected').text()
            var patron = 'factura'
            //tipoDocumento = tipoDocumento.toLowerCase()
            if(tipoDocumento.toLowerCase().indexOf('factura') != -1) {                
                $('.numero').show()
            } else {
                $('.numero').hide()                
            }
            //alert(tipoDocumento)
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

        $('#grabarProveedor').click(function () {
            var form = $('#form-save')
            var url = '{{ route('proveedores.store') }}'
            var data = form.serialize()
            alert(data);

            $.post(url, data, function (data) {
                //alert(data[2])
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

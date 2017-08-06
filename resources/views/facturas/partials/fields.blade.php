
@if (isset($liquidacion_id))
    <input id="LIQUIDACION_ID" name="LIQUIDACION_ID" type="hidden" value="{{ $liquidacion_id }}">
@endif

<div class="panel panel-default">
  <div class="panel-heading">Datos de la Factura </div>
  <div class="panel-body">
    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('TIPOGASTO_ID', 'Tipo de Gasto') !!}
      </div>
      <div class="col-md-3">
          {!! Form::select('TIPOGASTO_ID', $tipoGasto, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Tipo de Gasto', 'id' => 'tipoGasto']); !!}
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
          {!! Form::label('TIPODOCUMENTO', 'Tipo de Documento') !!}
      </div>
      <div class="col-md-2">
          {!! Form::radio('TIPODOCUMENTO', 1, true); !!}  Factura
      </div>
      <div class="col-md-1">
          {!! Form::radio('TIPODOCUMENTO', 2); !!} Recibo
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
        <div class="col-md-2">
            {!! Form::file('FOTO'); !!}
        </div>
    </div>

    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('FECHA_FACTURA', 'Fecha de la Factura') !!}
        </div>
        <div class="col-md-2">
            @if($fechaFactura)
                {!! Form::date('FECHA_FACTURA', $fechaFactura, ['class' => 'form-control']); !!}
            @else
                {!! Form::date('FECHA_FACTURA', null, ['class' => 'form-control']); !!}
            @endif
        </div>

    </div>

    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('SERIE', 'Serie Factura') !!}
        </div>
        <div class="col-md-3">
            {!! Form::text('SERIE', null, ['class' => 'form-control', 'placeholder' => 'Serie Factura']); !!}
        </div>
    </div>

    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('NUMERO', 'Número Factura') !!}
        </div>
        <div class="col-md-3">
            {!! Form::text('NUMERO', null, ['class' => 'form-control', 'placeholder' => 'Número Factura']); !!}
        </div>
    </div>

    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('TOTAL', 'Total') !!}
        </div>
        <div class="col-md-2">
            {!! Form::text('TOTAL', null, ['class' => 'form-control', 'placeholder' => 'Total Factura']); !!}
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
        <div class="col-md-1">
            <div id="etiqueta" style="font-weight: 700"></div>
        </div>
        <div class="col-md-1" id="cantidadPorcentaje" style="display: block">
            @if($factura->CANTIDAD_PORCENTAJE_CUSTOM)
                {!! Form::text('CANTIDAD_PORCENTAJE', $factura->CANTIDAD_PORCENTAJE_CUSTOM, ['class' => 'form-control', 'placeholder' => 'Cantidad']); !!}
            @else
                {!! Form::text('CANTIDAD_PORCENTAJE', null, ['class' => 'form-control', 'placeholder' => 'Cantidad']); !!}
            @endif
        </div>
        <div class="col-md-1">
            {!! Form::label('KM_INICIO', 'Km Inicial') !!}
        </div>
        <div class="col-md-1">
            @if($factura->KILOMETRAJE_INICIAL)
                {!! Form::text('KM_INICIO', $factura->KILOMETRAJE_INICIAL, ['class' => 'form-control', 'placeholder' => 'Inicio']); !!}
            @else
                {!! Form::text('KM_INICIO', null, ['class' => 'form-control', 'placeholder' => 'Inicio']); !!}
            @endif
        </div>
        <div class="col-md-1">
                {!! Form::label('KM_FINAL', 'Km Final') !!}
        </div>
        <div class="col-md-1">
            @if($factura->KILOMETRAJE_FINAL)
                {!! Form::text('KM_FINAL', $factura->KILOMETRAJE_FINAL, ['class' => 'form-control', 'placeholder' => 'Final']); !!}
            @else
                {!! Form::text('KM_FINAL', null, ['class' => 'form-control', 'placeholder' => 'Final']); !!}
            @endif
        </div>
    </div>



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
        }

        $('#tipoGasto').change(function () {
            var tipo = $('#tipoGasto').val();
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
        });

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

            $.post(url, data, function (data) {
                alert(data[2])
                $('#nit').html("<option value='" + data[2] + "'>" + data[0] + "</option>")
                //$('#nit').html(data[0])
                $('#proveedorNuevo').val(data[2])
                $('#nombreProveedor').val(data[1])
                $('#myModalA').modal('hide')
            })
        });

    });
</script>
@endpush

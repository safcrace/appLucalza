
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
      <div class="col-md-3">
          {!! Form::select('PROVEEDOR_ID', $proveedor, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Proveedor', 'id' => 'nit']); !!}
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
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('NOMBRE_PROVEEDOR', 'Nombre Proveedor') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('NOMBRE_PROVEEDOR', 'Nombre Proveedor', ['class' => 'form-control', 'disabled' => 'true', 'id' => 'nombreProveedor']); !!}
      </div>

    </div>


    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('SERIE', 'Serie Factura') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('SERIE', null, ['class' => 'form-control', 'placeholder' => 'Serie Factura']); !!}
      </div>
      <div class="col-md-2">
        {!! Form::label('MONEDA_ID', 'Moneda') !!}
      </div>
      <div class="col-md-2">
        {!! Form::radio('MONEDA_ID', $moneda->ID, true); !!}  {{ $moneda->DESCRIPCION }}
      </div>
      <div class="col-md-2">
        {!! Form::radio('MONEDA_ID', 2); !!}  DOLAR
      </div>
    </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('NUMERO', 'Número Factura') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('NUMERO', null, ['class' => 'form-control', 'placeholder' => 'Número Factura']); !!}
      </div>
      <div class="col-md-1 col-md-offset-1">
            {!! Form::label('TOTAL', 'Total') !!}
      </div>
      <div class="col-md-2">
          {!! Form::text('TOTAL', null, ['class' => 'form-control', 'placeholder' => 'Total Factura']); !!}
      </div>
    </div>

    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('FOTO', 'Imagen Factura') !!}
        </div>
        <div class="col-md-2">
            {!! Form::file('FOTO'); !!}
        </div>
      <div class="col-md-1 col-md-offset-2">
            <div id="etiqueta" style="font-weight: 700"></div>
      </div>
      <div class="col-md-2" id="cantidadPorcentaje" style="display: none">
          {!! Form::text('CANTIDAD_PORCENTAJE', null, ['class' => 'form-control', 'placeholder' => 'Ingrese Cantidad']); !!}
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

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {

        $('#tipoGasto').change(function () {
            var tipo = $('#tipoGasto').val();
            vurl = '{{ route('tipoGasto') }}'
            vurl = vurl.replace('%7Bid%7D', tipo);

            $.ajax({
                type:'get',
                url:vurl,
                success: function(data){
                    $('#etiqueta').html(data);
                    $('#cantidadPorcentaje').show();
                }
            });


        });

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

    });
</script>
@endpush

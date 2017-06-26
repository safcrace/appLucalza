
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
          {!! Form::select('TIPOGASTO_ID', $tipoGasto, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Tipo de Gasto']); !!}
      </div>

      <div class="col-md-2">
            {!! Form::label('PROVEEDOR_ID', 'Proveedor') !!}
      </div>
      <div class="col-md-3">
          {!! Form::select('PROVEEDOR_ID', $proveedor, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Proveedor']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('FECHA_FACTURA', 'Fecha de la Factura') !!}
      </div>
      <div class="col-md-2">
            {!! Form::date('FECHA_FACTURA', null, ['class' => 'form-control']); !!}
      </div>
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('NOMBRE_PROVEEDOR', 'Nombre Proveedor') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('NOMBRE_PROVEEDOR', 'Nombre Proveedor', ['class' => 'form-control', 'disabled' => 'true']); !!}
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
      <div class="col-md-3">
          {!! Form::select('MONEDA_ID', $moneda, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Moneda']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('NUMERO', 'Número Factura') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('NUMERO', null, ['class' => 'form-control', 'placeholder' => 'Número Factura']); !!}
      </div>
      <div class="col-md-2">
            {!! Form::label('TOTAL', 'Total') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('TOTAL', null, ['class' => 'form-control', 'placeholder' => 'Total Factura']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('CANTIDAD_PORCENTAJE', 'Propina/Galones') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('CANTIDAD_PORCENTAJE', null, ['class' => 'form-control', 'placeholder' => 'Cantidad de Galones/Propina']); !!}
      </div>
      <div class="col-md-2">
            {!! Form::label('FOTO', 'Imagen Factura') !!}
      </div>
      <div class="col-md-1">
        {!! Form::file('FOTO', null, ['class' => 'form-control']); !!}
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

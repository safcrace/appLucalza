<div class="row form-group">
    <div class="col-md-3 col-md-offset-1">
        {!! Form::label('TIPOPROVEDOR_ID', 'Tipo Proveedor') !!}
    </div>
    <div class="col-md-5">
        {!! Form::select('TIPOPROVEEDOR_ID', $tipoProveedor, 1, ['class' => 'form-control', 'placeholder' => 'Seleccione Tipo Proveedor', 'id' => 'tipoproveedor_id']); !!}
    </div>
    <div class="col-md-3">
        {!! Form::hidden('PROVEEDOR_ID', $factura->PROVEEDORID, ['class' => 'form-control', 'id' => 'proveedor_id']); !!}
    </div>
    <div class="col-md-3">
        {!! Form::hidden('NUMERO_LIQUIDACION', $liquidacion->ID, ['class' => 'form-control', 'id' => 'numero_liquidacion']); !!}
    </div>
</div>
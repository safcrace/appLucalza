@if (isset($empresa_id))
    <input id="EMPRESA_ID" name="EMPRESA_ID" type="hidden" value="{{ $empresa_id }}">
@endif


<div class="row form-group">
  <div class="col-md-2 col-md-offset-1">
        {!! Form::label('MONEDA ID', 'Moneda') !!}
  </div>
  <div class="col-md-3">
      {!! Form::select('MONEDA_ID', ['01' => 'Quetzal', '02' => 'Dolar', '03' => 'Euro'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Moneda']); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-2 col-md-offset-1">
        {!! Form::label('IDENTIFICADOR_TRIBUTARIO', 'Identificador Tributario') !!}
  </div>
  <div class="col-md-3">
      {!! Form::text('IDENTIFICADOR_TRIBUTARIO', null, ['class' => 'form-control', 'placeholder' => 'Identificador Tributario Proveedor']); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-2 col-md-offset-1">
        {!! Form::label('NOMBRE', 'Nombre') !!}
  </div>
  <div class="col-md-4">
      {!! Form::text('NOMBRE', null, ['class' => 'form-control', 'placeholder' => 'Nombre de Proveedor']); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-2 col-md-offset-1">
        {!! Form::label('DOMICILIO', 'Dirección') !!}
  </div>
  <div class="col-md-4">
      {!! Form::text('DOMICILIO', null, ['class' => 'form-control', 'placeholder' => 'Dirección de Proveedor']); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-2 col-md-offset-1">
        {!! Form::label('ANULADO', 'Anular') !!}
  </div>
  <div class="col-md-4">
      {!! Form::checkbox('ANULADO', 1); !!}
  </div>
</div>

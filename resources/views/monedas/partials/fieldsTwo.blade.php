@if (isset($moneda_id))
    <input id="MONEDA_ID" name="MONEDA_ID" type="hidden" value="{{ $moneda_id }}">
@endif

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('FECHA', 'Fecha') !!}
  </div>
  <div class="col-md-2">
      {!! Form::date('FECHA', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('COMPRA', 'Tasa de Cambio') !!}
  </div>
  <div class="col-md-4">
      {!! Form::text('COMPRA', null, ['class' => 'form-control', 'placeholder' => 'Tasa de Cambio']); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('ANULADOTC', 'Anular') !!}
  </div>
  <div class="col-md-4">
      {!! Form::checkbox('ANULADOTC', 1); !!}
  </div>
</div>

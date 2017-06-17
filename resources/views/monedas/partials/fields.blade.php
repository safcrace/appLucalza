<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('CLAVE', 'Código') !!}
  </div>
  <div class="col-md-2">
      {!! Form::text('CLAVE', null, ['class' => 'form-control', 'placeholder' => 'Código de la moneda']); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('DESCRIPCION', 'Nombre') !!}
  </div>
  <div class="col-md-4">
      {!! Form::text('DESCRIPCION', null, ['class' => 'form-control', 'placeholder' => 'Nombre de la moneda']); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('ANULADO', 'Anular') !!}
  </div>
  <div class="col-md-4">
      {!! Form::checkbox('ANULADO', 1); !!}
  </div>
</div>

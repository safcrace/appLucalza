@if (isset($usuario_id))
    <input id="USUARIO_ID" name="USUARIO_ID" type="hidden" value="{{ $usuario_id }}">
@endif
<hr>
<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('USUARIO', 'Usuario') !!}
  </div>
  <div class="col-md-3">
      {!! Form::text('USUARIO', $usuario, ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('RUTA_ID', 'Ruta') !!}
  </div>
  <div class="col-md-3">
          {!! Form::select('RUTA_ID', $rutas, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Ruta']); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('FECHA_INICIO', 'Fecha') !!}
  </div>
  <div class="col-md-2">
        {!! Form::date('FECHA_INICIO', null, ['class' => 'form-control']); !!}
  </div>

    {{--<div class="col-md-1 col-md-offset-1">
        {!! Form::label('ANULADO', 'Estatus') !!}
  </div>
  <div class="col-md-1">
      {!! Form::radio('ANULADO', 0); !!}  Alta
  </div>
  <div class="col-md-2">
      {!! Form::radio('ANULADO', 1); !!}  Baja
  </div>--}}


</div>
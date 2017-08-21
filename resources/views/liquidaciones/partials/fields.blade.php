@if (isset($usuario_id))
    <input id="USUARIO_ID" name="USUARIO_ID" type="hidden" value="{{ $usuario_id }}">
@endif

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('USUARIO', 'Usuario') !!}
  </div>
  <div class="col-md-3">
      {!! Form::text('USUARIO', $usuario, ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>

  <div class="col-md-1 ">
    {!! Form::label('RUTA_ID', 'Ruta') !!}
  </div>
  <div class="col-md-3">
    {!! Form::select('RUTA_ID', $rutas, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Ruta']); !!}
  </div>

</div>

<div class="row form-group">

</div>

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
    {!! Form::label('PRESUPUESTO_ID', 'No. Presupuesto') !!}
  </div>
  <div class="col-md-1">
    {!! Form::text('PRESUPUESTO_ID', null, ['class' => 'form-control']); !!}
  </div>
  <div class="col-md-1 col-md-offset-2">
        {!! Form::label('FECHA_INICIO', 'Fecha Inicio') !!}
  </div>
  <div class="col-md-2">
        {!! Form::date('FECHA_INICIO', null, ['class' => 'form-control']); !!}
  </div>
  <div class="col-md-1 ">
    {!! Form::label('FECHA_FINAL', 'Fecha Final') !!}
  </div>
  <div class="col-md-2">
    {!! Form::date('FECHA_FINAL', null, ['class' => 'form-control']); !!}
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

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
    {!! Form::label('COMENTARIO_PAGO', 'Comentario') !!}
  </div>
  <div class="col-md-8">
    {!! Form::textarea('COMENTARIO_PAGO', null, ['class' => 'form-control', 'rows' => '2', 'cols' => '500', 'placeholder' => 'Observaciones y/o Comentarios']); !!}
  </div>
</div>


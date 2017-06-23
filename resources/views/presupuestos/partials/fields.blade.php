
<hr>
<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('USUARIO_ID', 'Usuario') !!}
  </div>
  <div class="col-md-3">
      @if (isset($combos))
        {!! Form::select('USUARIO_ID', $usuarios, $combos->USUARIO, ['class' => 'form-control', 'placeholder' => 'Seleccione un Usuario']); !!}
      @else
        {!! Form::select('USUARIO_ID', $usuarios, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un Usuario']); !!}
      @endif
  </div>

  <div class="col-md-2 col-md-offset-2">
        {!! Form::label('MONEDA_ID', 'Moneda') !!}
  </div>
  <div class="col-md-2">
      {!! Form::text('MONEDA_ID', null, ['class' => 'form-control', 'placeholder' => 'Moneda']); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('RUTA_ID', 'Ruta') !!}
  </div>
  <div class="col-md-3">
      @if (isset($combos))
          {!! Form::select('RUTA_ID', $rutas, $combos->RUTA, ['class' => 'form-control', 'placeholder' => 'Seleccione una Ruta']); !!}
      @else
          {!! Form::select('RUTA_ID', $rutas, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Ruta']); !!}
      @endif
  </div>

  <div class="col-md-2 col-md-offset-2">
        {!! Form::label('VIGENCIA_INICIO', 'Vigencia Del') !!}
  </div>
  <div class="col-md-2">
      @if ($combos)
        {!! Form::date('VIGENCIA_INICIO', null, ['class' => 'form-control']); !!}
      @else
        {!! Form::date('VIGENCIA_INICIO', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
      @endif
  </div>
</div>

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('ANULADO', 'Estatus') !!}
  </div>
  <div class="col-md-1">
      {!! Form::radio('ANULADO', 0); !!}  Alta
  </div>
  <div class="col-md-2">
      {!! Form::radio('ANULADO', 1); !!}  Baja
  </div>

  <div class="col-md-2 col-md-offset-2">
        {!! Form::label('VIGENCIA_FINAL', 'Al ') !!}
  </div>
  <div class="col-md-2">
      @if ($combos)
        {!! Form::date('VIGENCIA_FINAL', null, ['class' => 'form-control']); !!}
      @else
        {!! Form::date('VIGENCIA_FINAL', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
      @endif

  </div>
</div>

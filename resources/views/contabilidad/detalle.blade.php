
<div class="row form-group">
  <div class="col-md-1">
        {!! Form::label('LIQUIDACION', 'No. ') !!}
  </div>
  <div class="col-md-2">
        {!! Form::text('LIQUIDACION', $liquidacion->ID, ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>
  <div class="col-md-1">
        {!! Form::label('RUTA_ID', 'Ruta') !!}
  </div>
  <div class="col-md-2">
        {!! Form::text('RUTA_ID', $liquidacion->RUTA, ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>

  <div class="col-md-1">
        {!! Form::label('TOTAL', 'Total') !!}
  </div>


  <div class="col-md-1 col-md-offset-2">
      <button type="button" class="btn btn-primary">Corregir</button>
  </div>
</div>

<div class="row form-group">
  <div class="col-md-1">
        {!! Form::label('USUARIO', 'Usuario') !!}
  </div>
  <div class="col-md-2">
        {!! Form::text('USUARIO', $liquidacion->USUARIO, ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>
  <div class="col-md-1">
        {!! Form::label('FECHA', 'Fecha') !!}
  </div>
  <div class="col-md-2">
        {!! Form::text('FECHA', $liquidacion->FECHA, ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>
  <div class="col-md-2">

        {!! Form::text('TOTAL', 'Q.' . App\Factura::where('LIQUIDACION_ID', '=', $liquidacion->ID)->sum('TOTAL'), ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>
  <div class="col-md-1 col-md-offset-1">
      <button type="button" class="btn btn-primary">Aprobar</button>
  </div>
  <div class="col-md-1">
      <button type="button" class="btn btn-primary">Imprimir</button>
  </div>
</div>

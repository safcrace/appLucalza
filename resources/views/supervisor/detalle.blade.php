
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
        {!! Form::text('FECHA', $liquidacion->FECHA_INICIO->format('d-m-Y'), ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>
  <div class="col-md-2">

        {!! Form::text('TOTAL', 'Q.' . App\Factura::where('LIQUIDACION_ID', '=', $liquidacion->ID)->sum('TOTAL'), ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>
  <div class="col-md-1 col-md-offset-1">
      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModalTwo"><span class="glyphicon glyphicon-floppy-remove" aria-hidden="true" style="font-size:32px; color: black"></span></button>
  </div>
  <div class="col-md-1">
    {!! Form::model($liquidacion, ['route' => ['aprobacionLiquidacion', $liquidacion->ID], 'method' => 'PATCH']) !!}
      <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true" style="font-size:32px; color: black"></button>
    {!! Form::close() !!}
  </div>
  <div class="col-md-1">
      <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-print" aria-hidden="true" style="font-size:32px; color: black"></button>
  </div>
</div>

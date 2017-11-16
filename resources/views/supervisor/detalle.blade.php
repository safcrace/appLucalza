
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

        {!! Form::text('TOTAL', 'Q.' . App\Factura::where('LIQUIDACION_ID', '=', $liquidacion->ID)->where('ANULADO', '=', 0)->sum('TOTAL'), ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>
  <div class="col-md-1 col-md-offset-1">
      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModalTwo"><span class="glyphicon glyphicon-floppy-remove" aria-hidden="true" style="font-size:32px; color: black" data-toggle="tooltip" data-placement="top" title="Corregir"></span></button>
  </div>
  <div class="col-md-1">
    {!! Form::model($liquidacion, ['route' => ['aprobacionLiquidacion', $liquidacion->ID], 'method' => 'PATCH']) !!}
      <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true" style="font-size:32px; color: black" data-toggle="tooltip" data-placement="top" title="Aprobar"></button>
    {!! Form::close() !!}
  </div>
  <div class="col-md-1">
      <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-print" aria-hidden="true" style="font-size:32px; color: black" data-toggle="tooltip" data-placement="top" title="Imprimr"></button>
  </div>
</div>


  <div class="row form-group">
    @if($liquidacion->SUPERVISOR_COMENTARIO)
        <div class="col-md-1">
          {!! Form::label('COMENTARIO_SUPERVISOR', 'Rechazo Supervisor') !!}
        </div>
        <div class="col-md-5">
          {!! Form::textarea('COMENTARIO_SUPERVISOR', $liquidacion->SUPERVISOR_COMENTARIO, ['class' => 'form-control', 'rows' => '2', 'cols' => '500', 'placeholder' => 'Observaciones y/o Comentarios', 'disabled' => 'true']); !!}
        </div>
    @endif
    @if($liquidacion->CONTABILIDAD_COMENTARIO)
        <div class="col-md-1">
          {!! Form::label('COMENTARIO_CONTABILIDAD', 'Rechazo Contabilidad') !!}
        </div>
        <div class="col-md-5">
          {!! Form::textarea('COMENTARIO_CONTABILIDAD', $liquidacion->CONTABILIDAD_COMENTARIO, ['class' => 'form-control', 'rows' => '2', 'cols' => '500', 'placeholder' => 'Observaciones y/o Comentarios', 'disabled' => 'true']); !!}
        </div>
    @endif
  </div>



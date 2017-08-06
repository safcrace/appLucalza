@if (isset($usuario_id))
    <input id="USUARIO_ID" name="USUARIO_ID" type="hidden" value="{{ $usuario_id }}">
@endif
<hr>
<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('USUARIO', 'Usuario') !!}
  </div>
  <div class="col-md-3">
      {!! Form::text('USUARIO', $usuario->nombre, ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>

  <div class="col-md-1 col-md-offset-1">
      {!! Form::label('RUTA_ID', 'Ruta') !!}
  </div>
  <div class="col-md-3">
      {!! Form::select('RUTA_ID', $rutas, $combo->RUTA, ['class' => 'form-control', 'placeholder' => 'Seleccione una Ruta']); !!}
  </div>
  <div class="col-md-1">
      <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-print" aria-hidden="true" style="font-size:32px; color: black" data-toggle="tooltip" data-placement="top" title="Imprimr"></button>
  </div>
</div>

<div class="row form-group">
    <div class="col-md-1 col-md-offset-1">
        {!! Form::label('FECHA_INICIO', 'Fecha Inicio') !!}
    </div>
    <div class="col-md-2">
        @if($liquidacion->FECHA_INICIO)
            {!! Form::date('FECHA_INICIO', $liquidacion->FECHA_INICIO, ['class' => 'form-control']); !!}
        @else
            {!! Form::date('FECHA_INICIO', null, ['class' => 'form-control']); !!}
        @endif
    </div>
    <div class="col-md-1">
        {!! Form::label('FECHA_FINAL', 'Fecha Final') !!}
    </div>
    <div class="col-md-2">
        @if($liquidacion->FECHA_FINAL)
            {!! Form::date('FECHA_FINAL', $liquidacion->FECHA_FINAL, ['class' => 'form-control']); !!}
        @else
            {!! Form::date('FECHA_FINAL', null, ['class' => 'form-control']); !!}
        @endif
    </div>

    <div class="col-md-1">
        {!! Form::label('TOTAL', 'TOTAL') !!}
    </div>
    <div class="col-md-2">
        {!! Form::text('TOTAL', 'Q.' . App\Factura::where('LIQUIDACION_ID', '=', $liquidacion->ID)->sum('TOTAL'), ['class' => 'form-control', 'disabled' => 'true']); !!}
    </div>

  <div class="col-md-1">

      <a href="{{ route('enviarLiquidacion', $liquidacion->ID) }}">
        <button type="button" class="btn btn-default" ><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true" style="font-size:32px; color: black" data-toggle="tooltip" data-placement="top" title="Enviar"></button>
      </a>
  </div>
</div>

<div class="row form-group">
    <div class="col-md-1 col-md-offset-1">
        {!! Form::label('COMENTARIO_PAGO', 'Comentario') !!}
    </div>
    <div class="col-md-8">
        {!! Form::textarea('COMENTARIO_PAGO', null, ['class' => 'form-control', 'rows' => '2', 'cols' => '500', 'placeholder' => 'Observaciones y/o Comentarios']); !!}
    </div>
  <div class="col-md-1">
      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModalTwo"><span class="glyphicon glyphicon-floppy-remove" aria-hidden="true" style="font-size:32px; color: black" data-toggle="tooltip" data-placement="top" title="Anular"></span></button>
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


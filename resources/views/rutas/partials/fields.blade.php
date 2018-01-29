@if (isset($empresa_id))
    <input id="EMPRESA_ID" name="EMPRESA_ID" type="hidden" value="{{ $empresa_id }}">
@endif

@if (isset($ruta->EMPRESA_ID))
    <input id="EMPRESA_ID" name="EMPRESA_ID" type="hidden" value="{{ $ruta->EMPRESA_ID }}">
@endif

{!! Form::hidden('TIPO_GASTO', $descripcion); !!}

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('CLAVE', 'Código') !!}
  </div>
  <div class="col-md-2">
      {!! Form::text('CLAVE', null, ['class' => 'form-control', 'placeholder' => 'Código de ' . substr($descripcion, 0, -1)]); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('DESCRIPCION', 'Nombre') !!}
  </div>
  <div class="col-md-4">
      {!! Form::text('DESCRIPCION', null, ['class' => 'form-control', 'placeholder' => 'Nombre de ' . substr($descripcion, 0, -1) ]); !!}
  </div>
</div>

@IF($descripcion != 'Rutas')
  <div class="row form-group" >
    <div class="col-md-1 col-md-offset-1">
      {!! Form::label('ASIGNACIONPRESUPUESTO_ID', 'Tipo Gasto') !!}
    </div>
    <div class="col-md-3">
      {!! Form::radio('ASIGNACIONPRESUPUESTO_ID', 1, ['id' => 'dinero']); !!}  ADMINISTRATIVO
    </div>
    <div class="col-md-2">
      {!! Form::radio('ASIGNACIONPRESUPUESTO_ID', 2, ['id' => 'unidad']); !!}  DEPRECIACION
    </div>
  </div>
@endif

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('ANULADO', 'Anular') !!}
  </div>
  <div class="col-md-4">
      {!! Form::checkbox('ANULADO', 1); !!}
  </div>
</div>

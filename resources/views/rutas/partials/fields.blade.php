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
      {!! Form::text('CLAVE', null, ['class' => 'form-control', 'placeholder' => ($descripcion == 1) ? 'Código de Ruta' : 'Código de Gasto']); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('DESCRIPCION', 'Nombre') !!}
  </div>
  <div class="col-md-4">
      {!! Form::text('DESCRIPCION', null, ['class' => 'form-control', 'placeholder' => ($descripcion == 1) ? 'Nombre de Ruta' : 'Nombre de Gasto' ]); !!}
  </div>
</div>

@IF($descripcion != 1)
  <div class="row form-group" >
    <div class="col-md-1 col-md-offset-1">
      {!! Form::label('DEPRECIACION', 'Tipo Gasto') !!}
    </div>
    @if(isset($ruta))
      <div class="col-md-3">
        {!! Form::radio('DEPRECIACION', 0, ['id' => 'dinero']); !!}  ADMINISTRATIVO
      </div>
      <div class="col-md-2">
          {!! Form::radio('DEPRECIACION', 1, ['id' => 'unidad']); !!}  DEPRECIACION
      </div>
    @else
        <div class="col-md-3">
          {!! Form::radio('DEPRECIACION', 0, true, ['id' => 'dinero']); !!}  ADMINISTRATIVO
        </div>
        <div class="col-md-2">
          {!! Form::radio('DEPRECIACION', 1, false, ['id' => 'unidad']); !!}  DEPRECIACION
        </div>
    @endif
  </div>
@endif

{{-- <div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('ANULADO', 'Anular') !!}
  </div>
  <div class="col-md-4">
      {!! Form::checkbox('ANULADO', 1); !!}
  </div>
</div> --}}

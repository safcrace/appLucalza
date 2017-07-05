
<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('SUPERVISOR_ID', 'Supervisor') !!}
  </div>
  <div class="col-md-3">
        {!! Form::select('SUPERVISOR_ID', $supervisores, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un Supervisor', 'id' => 'supervisor']); !!}
  </div>

  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('VENDEDOR_ID', 'Vendedor') !!}
  </div>
  <div class="col-md-3">
        {!! Form::select('VENDEDOR_ID', $vendedores, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un Vendedor']); !!}
  </div>
</div>

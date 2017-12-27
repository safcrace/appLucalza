
<div class="row form-group">
    <div class="col-md-2 ">
        {!! Form::label('fechaOrigen', 'Fecha origen') !!}
    </div>
    <div class="col-md-3">
        {!! Form::date('fechaOrigen', null, ['class' => 'form-control', 'id' => 'fechaConsulta']); !!} 
    </div>
    <div class="col-md-2 ">
        <span class="glyphicon glyphicon-import" aria-hidden="true" id="cargaTasa" style="cursor: pointer"></span>
    </div>
</div>

<div class="row form-group">
    <div class="col-md-2 ">
        {!! Form::label('tasaCambio', 'Tasa de Cambio') !!}        
    </div>
    <div class="col-md-2">
        {!! Form::text('tasaCambio', null, ['class' => 'form-control', 'id' => 'tasaCambio']);!!}
    </div>
</div>


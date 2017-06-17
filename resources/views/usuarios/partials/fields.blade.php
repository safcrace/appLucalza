@if (isset($empresa_id))
    <input id="EMPRESA_ID" name="EMPRESA_ID" type="hidden" value="{{ $empresa_id }}">
@endif

<div class="panel panel-default">
  <div class="panel-heading">Datos Personales</div>

  <div class="panel-body">

    <div class="row form-group">
      <div class="col-md-1 col-md-offset-1">
            {!! Form::label('nombre', 'Nombre') !!}
      </div>
      <div class="col-md-5 col-md-offset-1">
          {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('email', 'Correo Electrónico') !!}
      </div>
      <div class="col-md-3">
          {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Correo Electrónico']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('tel_codpais', 'Código Pais Télefono') !!}
      </div>
      <div class="col-md-2">
          {!! Form::text('tel_codpais', null, ['class' => 'form-control', 'placeholder' => 'Código Pais']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('telefono', 'Télefono(s)') !!}
      </div>
      <div class="col-md-2">
          {!! Form::text('telefono', null, ['class' => 'form-control', 'placeholder' => 'Télefono(s)']); !!}
      </div>
    </div>

  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">Datos Usuario</div>

  <div class="panel-body">

    <div class="row form-group">
      <div class="col-md-1 col-md-offset-1">
            {!! Form::label('usuario', 'Usuario') !!}
      </div>
      <div class="col-md-5 col-md-offset-1">
          {!! Form::text('usuario', null, ['class' => 'form-control', 'placeholder' => 'Usuario']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-1 col-md-offset-1">
            {!! Form::label('password', 'Contraseña') !!}
      </div>
      <div class="col-md-3 col-md-offset-1">
          {!! Form::password('password', ['class' => 'form-control awesome', 'placeholder' => 'Contraseña']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('activo', 'Estatus') !!}
      </div>
      <div class="col-md-1">
          {!! Form::radio('activo', 1); !!}  Activo
      </div>
      <div class="col-md-2">
          {!! Form::radio('activo', 0); !!}  Inactivo
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-1 col-md-offset-1">
            {!! Form::label('ANULADO', 'Anular') !!}
      </div>
      <div class="col-md-4 col-md-offset-1">
          {!! Form::checkbox('ANULADO', 1); !!}
      </div>
    </div>

  </div>
</div>
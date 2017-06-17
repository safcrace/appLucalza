<div class="panel panel-default">
  <div class="panel-heading">Datos Empresa</div>

  <div class="panel-body">

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('CLAVE', 'Código Empresa') !!}
      </div>
      <div class="col-md-2">
          {!! Form::text('CLAVE', null, ['class' => 'form-control', 'placeholder' => 'Código de Empresa']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('DESCRIPCION', 'Nombre Empresa') !!}
      </div>
      <div class="col-md-4">
          {!! Form::text('DESCRIPCION', null, ['class' => 'form-control', 'placeholder' => 'Nombre de Empresa']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('ANULADO', 'Estatus') !!}
      </div>
      <div class="col-md-1">
          {!! Form::radio('ANULADO', 0); !!}  Activo
      </div>
      <div class="col-md-2">
          {!! Form::radio('ANULADO', 1); !!}  Inactivo
      </div>
    </div>


  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">Datos SAP</div>

  <div class="panel-body">

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('LICENSESERVER', 'License Server') !!}
      </div>
      <div class="col-md-2">
          {!! Form::text('LICENSESERVER', null, ['class' => 'form-control', 'placeholder' => 'License Server SAP']); !!}
      </div>
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('DBSAP', 'Base de Datos SAP') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('DBSAP', null, ['class' => 'form-control', 'placeholder' => 'Base Datos SAP']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('USERSAP', 'Usuario SAP') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('USERSAP', null, ['class' => 'form-control', 'placeholder' => 'Usuario SAP']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('PASSSAP', 'Contraseña SAP') !!}
      </div>
      <div class="col-md-3">
          {!! Form::password('PASSSAP', ['class' => 'form-control awesome', 'placeholder' => 'Contraseña SAP']); !!}
      </div>
    </div>


  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">Datos SQL</div>

  <div class="panel-body">

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('USERSQL', 'Usuario SQL') !!}
      </div>
      <div class="col-md-2">
          {!! Form::text('USERSQL', null, ['class' => 'form-control', 'placeholder' => 'Usuario SQL']); !!}
      </div>
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('SAPDBTYPE', 'SAP DB Type') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('SAPDBTYPE', null, ['class' => 'form-control', 'placeholder' => 'SAP DB Type']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('PASSSQL', 'Constraseñ SQL') !!}
      </div>
      <div class="col-md-3">
          {!! Form::password('PASSSQL', ['class' => 'form-control awesome', 'placeholder' => 'Contraseña SQL']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('SERVIDORSQL', 'Servidor SQL') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('SERVIDORSQL', null, ['class' => 'form-control', 'placeholder' => 'Servidor SQL']); !!}
      </div>
    </div>


  </div>
</div>
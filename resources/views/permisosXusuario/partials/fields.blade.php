<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('usuario', 'Usuario') !!}
  </div>
  <div class="col-md-3">
        @if (isset($usuario))
            {!! Form::select('usuario', $usuarios, $usuario, ['class' => 'form-control', 'placeholder' => 'Seleccione un Usuario', 'id' => 'usuario']); !!}
        @else
            {!! Form::select('usuario', $usuarios, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un Usuario', 'id' => 'usuario']); !!}
        @endif

  </div>
</div>


<div class="row form-group">

  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('list_permission', 'Permisos') !!}
  </div>
  <div class="col-md-8">
        @if (isset($autorizados))
            {!! Form::select('list_permission[]', $permisos, $autorizados, ['class' => 'form-control', 'multiple' => 'true',  'id' => 'permisosUsuario']); !!}
        @else            
            {!! Form::select('list_permission[]', $permisos, null, ['class' => 'form-control', 'multiple' => 'true', 'id' => 'permisosUsuario']); !!}
        @endif
  </div>
</div>

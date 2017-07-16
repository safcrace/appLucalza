
<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('role', 'Rol') !!}
  </div>
  <div class="col-md-3">
        @if (isset($role))
            {!! Form::select('role', $roles, $role, ['class' => 'form-control', 'placeholder' => 'Seleccione un Rol', 'id' => 'role']); !!}
        @else
            {!! Form::select('role', $roles, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un Rol', 'id' => 'role']); !!}
        @endif

  </div>
</div>


<div class="row form-group">

  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('permission_list', 'Permisos') !!}
  </div>
  <div class="col-md-8">
        @if (isset($autorizados))          
            {!! Form::select('permission_list[]', $permisos, $autorizados, ['class' => 'form-control', 'multiple' => 'true',  'id' => 'permisos']); !!}
        @else
            {!! Form::select('permission_list[]', $permisos, null, ['class' => 'form-control', 'multiple' => 'true', 'id' => 'permisos']); !!}
        @endif
  </div>
</div>

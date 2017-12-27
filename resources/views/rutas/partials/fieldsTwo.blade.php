@if (isset($usuario_id))
    <input id="USUARIO_ID" name="USUARIO_ID" type="hidden" value="{{ $usuario_id }}">
@endif

{!! Form::hidden('TIPO_GASTO', $descripcion); !!}
<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        @if($descripcion == 'Rutas')
          {!! Form::label('RUTA_ID', 'Ruta') !!}
        @else
          {!! Form::label('RUTA_ID', 'Otros Gastos') !!}
        @endif
  </div>
  <div class="col-md-3">
      {{--@if(isset($combo_id))
        {!! Form::select('RUTA_ID', $rutas, 0, ['class' => 'form-control', 'placeholder' => 'Seleccione una Opción']); !!}
      @else--}}
        {!! Form::select('RUTA_ID', $rutas, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Opción', 'id' => 'rutaUsuario']); !!}
      {{--@endif--}}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('ANULADO', 'Anular') !!}
  </div>
  <div class="col-md-4">
      {!! Form::checkbox('ANULADO', 1); !!}
  </div>
</div>

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {     

         $('#rutaUsuario').select2({
            placeholder: 'Seleccione un una Opción'
        })
    });
</script>
@endpush
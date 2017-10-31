
<hr>
<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('USUARIO_ID', 'Usuario') !!}
  </div>
  <div class="col-md-3">
      @if (isset($usuario_id))
        {!! Form::select('USUARIO_ID', $usuarios, $usuario_id, ['class' => 'form-control', 'placeholder' => 'Seleccione un Usuario', 'id' => 'usuario']); !!}
      @else
        {!! Form::select('USUARIO_ID', $usuarios, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un Usuario', 'id' => 'usuario']); !!}
      @endif
  </div>

    {!! Form::hidden('TIPO_GASTO', $tipoGasto, ['id' => 'tipo']) !!}

  <div class="col-md-2 col-md-offset-2">
        {!! Form::label('MONEDA_ID', 'Moneda') !!}
  </div>
  <div class="col-md-2">
    {!! Form::radio('MONEDA_ID', $moneda->ID, true); !!}  {{ $moneda->DESCRIPCION }}
  </div>
  <div class="col-md-2">
    {!! Form::radio('MONEDA_ID', 2); !!}  DOLAR
  </div>
</div>

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('RUTA_ID', $tipoGasto) !!}
  </div>
  <div class="col-md-3">
      @if (isset($ruta_id))
          {!! Form::select('RUTA_ID', $rutas, $ruta_id, ['class' => 'form-control', 'placeholder' => 'Seleccione una Opción', 'id' => 'ruta']); !!}
      @else
          {!! Form::select('RUTA_ID', $rutas, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Opción', 'id' => 'ruta']); !!}
      @endif

  </div>

  <div class="col-md-2 col-md-offset-2">
        {!! Form::label('VIGENCIA_INICIO', 'Vigencia Del') !!}
  </div>
  <div class="col-md-2">
        @if(isset($vigenciaInicio))
            {!! Form::date('VIGENCIA_INICIO', $vigenciaInicio, ['class' => 'form-control']); !!}
        @else
          {!! Form::date('VIGENCIA_INICIO', null, ['class' => 'form-control']); !!}
        @endif


  </div>
</div>


<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('ANULADO', 'Estatus') !!}
  </div>
  <div class="col-md-1">
      {!! Form::radio('ANULADO', 0, true); !!}  Alta
  </div>
  <div class="col-md-2">
      {!! Form::radio('ANULADO', 1); !!}  Baja
  </div>

  <div class="col-md-2 col-md-offset-2">
        {!! Form::label('VIGENCIA_FINAL', 'Al ') !!}
  </div>
  <div class="col-md-2">

        {!! Form::date('VIGENCIA_FINAL', $vigenciaFinal, ['class' => 'form-control']); !!}



  </div>
</div>

<div class="row form-group" id="asignacion" style="display: none">
    <div class="col-md-2 col-md-offset-1">
        {!! Form::label('ASIGNACION_MENSUAL', 'Asignación Mensual', ['id' => 'monto']) !!}
    </div>
    <div class="col-md-3">
        {!! Form::text('ASIGNACION_MENSUAL', null, ['class' => 'form-control', 'placeholder' => 'Ingrese Asignación']); !!}
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var role = $('#role').val();
        var tipo = $('#ruta option:selected').text()
        tipo = tipo.toLowerCase(tipo)

        if (tipo.indexOf('depre') != -1) {
            $('#asignacion').show()
        }else{

        }

        $('#usuario').change(function () {
            var usuario = $('#usuario').val();
            var tipoGasto = $('#tipo').val();
            vurl = '{{ route('presupuestos.show') }}' + usuario + '-' + tipoGasto;
            vurl = vurl.replace('%7Bpresupuestos%7D', '');
            location.href = vurl;
        });

        $('#ruta').change(function () {
            var tipo = $('#ruta option:selected').text()
            tipo = tipo.toLowerCase(tipo)

            if (tipo.indexOf('depre') != -1) {
                $('#asignacion').show()
            }else{

            }


        });

        $('#supervisor').select2();
        placeholder: 'Seleccione un Supervisor';
    });

    $('#permisos').select2({
        placeholder: "Seleccione un Permiso",
        allowClear: true
    });

    $('#permisosUsuario').select2({
        placeholder: "Seleccione un Permiso",
        allowClear: true
    });

</script>
@endpush

{{--
@if (isset($combos))
  {!! Form::select('USUARIO_ID', $usuarios, $combos->USUARIO, ['class' => 'form-control', 'placeholder' => 'Seleccione un Usuario', 'id' => 'usuario']); !!}
@else
  {!! Form::select('USUARIO_ID', $usuarios, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un Usuario', 'id' => 'usuario']); !!}
@endif

@if (isset($combos))
          {!! Form::select('RUTA_ID', $rutas, $combos->RUTA, ['class' => 'form-control', 'placeholder' => 'Seleccione una Ruta']); !!}
      @else
          {!! Form::select('RUTA_ID', $rutas, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Ruta']); !!}
      @endif

@if ($combos)
        {!! Form::date('VIGENCIA_INICIO', null, ['class' => 'form-control']); !!}
      @else
        {!! Form::date('VIGENCIA_INICIO', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
      @endif

@if ($combos)
        {!! Form::date('VIGENCIA_FINAL', null, ['class' => 'form-control']); !!}
      @else
        {!! Form::date('VIGENCIA_FINAL', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
      @endif

--}}
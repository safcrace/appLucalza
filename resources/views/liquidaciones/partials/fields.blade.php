@if (isset($usuario_id))
    <input id="usuarioId" name="USUARIO_ID" type="hidden" value="{{ $usuario_id }}">
@endif

{!! Form::hidden('TIPO_LIQUIDACION', $tipoLiquidacion, ['id' => 'tipoLiquidacion']) !!}

<div class="row form-group">  
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('FECHA_INICIO', 'Fecha Inicio') !!}
  </div>
  <div class="col-md-2">
        {!! Form::date('FECHA_INICIO', null, ['class' => 'form-control', 'id' => 'fechaIni']); !!}
  </div>
  <div class="col-md-1 col-md-offset-1">
    {!! Form::label('FECHA_FINAL', 'Fecha Final') !!}
  </div>
  <div class="col-md-2">
    {!! Form::date('FECHA_FINAL', null, ['class' => 'form-control', 'id' => 'fechaFin']); !!}
  </div>
    {{--<div class="col-md-1 col-md-offset-1">
        {!! Form::label('ANULADO', 'Estatus') !!}
  </div>
  <div class="col-md-1">
      {!! Form::radio('ANULADO', 0); !!}  Alta
  </div>
  <div class="col-md-2">
      {!! Form::radio('ANULADO', 1); !!}  Baja
  </div>--}}
</div>

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('USUARIO', 'Usuario') !!}
  </div>
  <div class="col-md-3">
      {!! Form::text('USUARIO', $usuario, ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>

  <div class="col-md-1 ">
    {!! Form::label('RUTA_ID', $tipoLiquidacion) !!}
  </div>
  <div class="col-md-3">
    {!! Form::select('RUTA_ID', $rutas, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Opción', 'id' => 'newRutas']); !!}
  </div>

</div>

<div class="row form-group">

</div>


<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
    {!! Form::label('COMENTARIO_PAGO', 'Comentario') !!}
  </div>
  <div class="col-md-8">
    {!! Form::textarea('COMENTARIO_PAGO', null, ['class' => 'form-control', 'rows' => '2', 'cols' => '500', 'placeholder' => 'Observaciones y/o Comentarios']); !!}
  </div>
</div>

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {

      $.fn.populateSelect = function (values) {
            var options = ''
            $.each(values, function (key, row) {
                options += '<option value = "' + row.ID + '">' + row.DESCRIPCION + '</option>'
            })
            $(this).html(options)
        }

      $('#fechaFin').blur(function() {
            var fechaInicio = $('#fechaIni').val();
            var fechaFinal = $('#fechaFin').val();
            var tipoLiquidacion = $('#tipoLiquidacion').val();
            var usuarioId = $('#usuarioId').val();
            vurl = '{{ route('rutasPresupuestadas') }}'
            vurl = vurl.replace('%7Bid%7D', fechaInicio + '&' + fechaFinal + '&' + tipoLiquidacion + '&' + usuarioId)            

            $.getJSON(vurl, null, function (values) {
                $('#newRutas').populateSelect(values)
            })
            
      })

       $('#newRutas').select2({
            placeholder: 'Seleccione una ruta'
        })
        
    });
</script>
@endpush
@if (isset($detallePresupuesto->PRESUPUESTO_ID))
    <input id="PRESUPUESTO_ID" name="PRESUPUESTO_ID" type="hidden" value="{{ $detallePresupuesto->PRESUPUESTO_ID }}">
@endif
@if (isset($presupuesto_id))
    <input id="PRESUPUESTO_ID" name="PRESUPUESTO_ID" type="hidden" value="{{ $presupuesto_id }}">
@endif

<div class="panel panel-primary">
  <div class="panel-heading">Detalle Presupuesto </div>
  <div class="panel-body">
    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('TIPOGASTO_ID', 'Tipo de Gasto') !!}
      </div>
      <div class="col-md-3">
          {!! Form::select('TIPOGASTO_ID', $tipoGasto, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Tipo de Gasto', 'id' => 'tipo']); !!}
      </div>
      <div class="col-md-2 asignacion" style="display: none">
        {!! Form::label('TIPOASIGNACION_ID', 'Tipo de Asignación') !!}
      </div>
      <div class="col-md-3 asignacion" style="display: none">
        {!! Form::select('TIPOASIGNACION_ID', $tipoAsignacion, 1, ['class' => 'form-control', 'placeholder' => 'Seleccione Tipo de Asignación', 'id' => 'tipoAsignacion']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('MONTO', 'Asignación', ['id' => 'monto']) !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('MONTO', null, ['class' => 'form-control', 'placeholder' => 'Asignación']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-1 col-md-offset-1">
            {!! Form::label('FRECUENCIATIEMPO_ID', 'Frecuencia') !!}
      </div>
      <div class="col-md-3 col-md-offset-1" >
              {!! Form::select('FRECUENCIATIEMPO_ID', $frecuencia, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Frecuencia']); !!}
      </div>
    </DIV>
    <hr>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('CENTROCOSTO1', 'Centro de Costo1') !!}
      </div>
      <div class="col-md-3" id="cc_1">
          {!! Form::text('CENTROCOSTO1', null, ['class' => 'form-control', 'placeholder' => 'Centro de Costo 1']); !!}
      </div>
      <div class="col-md-3" id="cc1_sap" style="display: none">

      </div>
      <div class="col-md-1">
        <span class="glyphicon glyphicon-import" aria-hidden="true" id="centro_1"></span>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('CENTROCOSTO2', 'Centro de Costo2') !!}
      </div>
      <div class="col-md-3" id="cc_2">
          {!! Form::text('CENTROCOSTO2', null, ['class' => 'form-control', 'placeholder' => 'Centro de Costo 2']); !!}
      </div>

      <div class="col-md-3" id="cc2_sap" style="display: none">

      </div>
      <div class="col-md-1">
        <span class="glyphicon glyphicon-import" aria-hidden="true" id="centro_2"></span>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('CENTROCOSTO3', 'Centro de Costo3') !!}
      </div>
      <div class="col-md-3" id="cc_3">
          {!! Form::text('CENTROCOSTO3', null, ['class' => 'form-control', 'placeholder' => 'Centro de Costo 3']); !!}
      </div>
      <div class="col-md-3" id="cc3_sap" style="display: none">

      </div>
      <div class="col-md-1">
        <span class="glyphicon glyphicon-import" aria-hidden="true" id="centro_3"></span>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('CENTROCOSTO4', 'Centro de Costo4') !!}
      </div>
      <div class="col-md-3" id="cc_4">
          {!! Form::text('CENTROCOSTO4', null, ['class' => 'form-control', 'placeholder' => 'Centro de Costo 4']); !!}
      </div>

      <div class="col-md-3" id="cc4_sap" style="display: none">

      </div>
      <div class="col-md-1">
        <span class="glyphicon glyphicon-import" aria-hidden="true" id="centro_4"></span>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('CENTROCOSTO5', 'Centro de Costo5') !!}
      </div>
      <div class="col-md-3" id="cc_5">
          {!! Form::text('CENTROCOSTO5', null, ['class' => 'form-control', 'placeholder' => 'Centro de Costo 5']); !!}
      </div>

      <div class="col-md-3" id="cc5_sap" style="display: none">

      </div>
      <div class="col-md-1">
        <span class="glyphicon glyphicon-import" aria-hidden="true" id="centro_5"></span>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('ANULADODP', 'Anular') !!}
      </div>
      <div class="col-md-4">
          {!! Form::checkbox('ANULADOPD', 1); !!}
      </div>
    </div>

  </div>
</div>



@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var tipo = $('#tipo').val()
        if(tipo != null) {
            if ((tipo == 2) || (tipo == 3) || (tipo == 6)) {
                $('.asignacion').show()
            } else {
                $('.asignacion').hide()
            }
        }
        $('#tipo').change(function () {
            var tipo = $('#tipo').val()
            if ((tipo == 2) || (tipo == 3) || (tipo == 6)) {
                $('.asignacion').show()
            } else {
                $('.asignacion').hide()
            }
        });
        $('#tipoAsignacion').change(function() {
            var tipoAsignacion = $('#tipoAsignacion').val()
            if (tipoAsignacion == '2') {
                $('#monto').html('Asignación Galones')
            } else if (tipoAsignacion == '3') {
                $('#monto').html('Asignación Kilometros')
            } else {
                $('#monto').html('Asignación')
            }
        })

        $('#centro_1').click(function() {
            vurl = '{{ route('codigoCentroCostoUno')}}'
            vurl = vurl.replace('%7Bid%7D', 4);

            $.ajax({
                type: 'get',
                url: vurl,
                success: function (data) {
                    $('#cc_1').remove()
                    $('#cc1_sap').html(data);
                    $('#cc1_sap').show()
                }
            })
        })

        $('#centro_2').click(function() {
            vurl = '{{ route('codigoCentroCostoDos')}}'
            vurl = vurl.replace('%7Bid%7D', 4);

            $.ajax({
                type: 'get',
                url: vurl,
                success: function (data) {
                    $('#cc_2').remove()
                    $('#cc2_sap').html(data);
                    $('#cc2_sap').show()
                }
            })
        })

        $('#centro_3').click(function() {
            vurl = '{{ route('codigoCentroCostoTres')}}'
            vurl = vurl.replace('%7Bid%7D', 4);

            $.ajax({
                type: 'get',
                url: vurl,
                success: function (data) {
                    $('#cc_3').remove()
                    $('#cc3_sap').html(data);
                    $('#cc3_sap').show()
                }
            })
        })

        $('#centro_4').click(function() {
            vurl = '{{ route('codigoCentroCostoCuatro')}}'
            vurl = vurl.replace('%7Bid%7D', 4);

            $.ajax({
                type: 'get',
                url: vurl,
                success: function (data) {
                    $('#cc_4').remove()
                    $('#cc4_sap').html(data);
                    $('#cc4_sap').show()
                }
            })
        })

        $('#centro_5').click(function() {
            vurl = '{{ route('codigoCentroCostoCinco')}}'
            vurl = vurl.replace('%7Bid%7D', 4);

            $.ajax({
                type: 'get',
                url: vurl,
                success: function (data) {
                    $('#cc_5').remove()
                    $('#cc5_sap').html(data);
                    $('#cc5_sap').show()
                }
            })
        })

    });
</script>
@endpush
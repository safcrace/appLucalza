@if (isset($empresa_id))
    <input id="EMPRESA_ID" name="EMPRESA_ID" type="hidden" value="{{ $empresa_id }}">
@endif
{!! Form::hidden('CAUSAEXENCION_ID', 1, ['class' => 'form-control']); !!}
<div class="row form-group">
    <div class="col-md-2 col-md-offset-1">
        {!! Form::label('GRUPO_ID', 'Grupo') !!}
    </div>
    <div class="col-md-3">
        @if(isset($tipoGasto->GRUPOTIPOGASTO_ID))
            {!! Form::select('GRUPO_ID', $grupo, $tipoGasto->GRUPOTIPOGASTO_ID, ['class' => 'form-control', 'placeholder' => 'Seleccione un Grupo']); !!}
        @else
            {!! Form::select('GRUPO_ID', $grupo, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un Grupo']); !!}
        @endif
    </div>
</div>
<div class="row form-group">
  <div class="col-md-2 col-md-offset-1">
        {!! Form::label('DESCRIPCION', 'Descripción') !!}
  </div>
  <div class="col-md-3">
      {!! Form::text('DESCRIPCION', null, ['class' => 'form-control', 'placeholder' => 'Descripción Gasto']); !!}
  </div>
</div>
    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('OPCIONCOMBUSTIBLE_ID', 'Control Combustible') !!}
        </div>
        <div class="col-md-1">
            {!! Form::radio('OPCIONCOMBUSTIBLE_ID', 1, false, ['id' => 'gas']); !!}  SI
        </div>
        <div class="col-md-1">
            {!! Form::radio('OPCIONCOMBUSTIBLE_ID', 2, true, ['id' => 'no_gas']); !!}  NO
        </div>
    </div>

<div class="row form-group" id="Kilometros" style="display: none">
    <div class="col-md-2 col-md-offset-1">
        {!! Form::label('OPCIONKILOMETRAJE_ID', 'Control Kilometraje') !!}
    </div>
    <div class="col-md-1">
        {!! Form::radio('OPCIONKILOMETRAJE_ID', 1, true, ['id' => 'kilometraje']); !!}  SI
    </div>
    <div class="col-md-1">
        {!! Form::radio('OPCIONKILOMETRAJE_ID', 2, false, ['id' => 'no_kilometraje']); !!}  NO
    </div>
</div>

{{--

<div class="row form-group">
    <div class="col-md-2 col-md-offset-1">
        {!! Form::label('ASIGNACIONPRESUPUESTO_ID', 'Asignación') !!}
    </div>
    <div class="col-md-2">
        {!! Form::radio('ASIGNACIONPRESUPUESTO_ID', 1, true, ['id' => 'asignacion']); !!}  DINERO
    </div>
    <div class="col-md-2">
        {!! Form::radio('ASIGNACIONPRESUPUESTO_ID', 2, false, ['id' => 'no_asignacion']); !!}  UNIDAD
    </div>
</div>

  <div class="col-md-1 col-md-offset-1">
    {!! Form::label('EXENTO', 'Exento') !!}
  </div>
  <div class="col-md-1">
    {!! Form::radio('EXENTO', 1, false, ['id' => 'exento']); !!}  SI
  </div>
  <div class="col-md-1">
    {!! Form::radio('EXENTO', 0, false, ['id' => 'no_exento']); !!}  NO
  </div>
</div>

<div class="row form-group">
  <div class="col-md-2 col-md-offset-1 div_exento" style="display: none">
      {!! Form::radio('CAUSAEXENCION_ID', 2, false, ['id' => 'cantidad']); !!}  CANTIDAD
  </div>
  <div class="col-md-2 ph_cantidad" style="display: none">
      {!! Form::text('MONTO_A_APLICAR_CANTIDAD', 1, ['class' => 'form-control', 'placeholder' => 'Cantidad']); !!}
  </div>
  <div class="col-md-2 ph_cantidad" style="display: none" >
      {!! Form::text('UNIDAD_MEDIDA', null, ['class' => 'form-control', 'placeholder' => 'Unidad de Medida']); !!}
  </div>
  <div class="col-md-2 ph_cantidad" style="display: none">
    {!! Form::label('OPCIONKILOMETRAJE_ID', 'Control Kilometraje') !!}
  </div>
  <div class="col-md-1 ph_cantidad" style="display: none">
    {!! Form::radio('OPCIONKILOMETRAJE_ID', 1, true); !!}  SI
  </div>
</div>

<div class="row form-group">
  <div class="col-md-2 col-md-offset-1 div_exento" style="display: none">
      {!! Form::radio('CAUSAEXENCION_ID', 3, false, ['id' => 'porcentaje']); !!}  PORCENTAJE
  </div>

  <div class="col-md-2" style="display: none" id="ph_porcentaje">
      {!! Form::text('MONTO_A_APLICAR_PORCENTAJE', null, ['class' => 'form-control', 'placeholder' => 'Porcentaje']); !!}
  </div>

  <div class="col-md-1 col-md-offset-6 ph_cantidad" style="display: none">
    {!! Form::radio('OPCIONKILOMETRAJE_ID', 2); !!} NO
  </div>
</div>
--}}
<hr>

<div class="row">
  <div class="col-md-4">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Exento</h3>
      </div>
      <div class="panel-body">
        <div class="row form-group text-center">
                {!! Form::label('CUENTA_CONTABLE_EXENTO', 'Cuenta Contable') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true" id="cc_exento"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::hidden('CUENTA_CONTABLE_EXENTO', null, ['class' => 'form-control', 'id' => 'cuenta_contable_exenta']); !!}
                {!! Form::text('DESCRIPCION_CUENTA_EXENTO', null, ['class' => 'form-control', 'placeholder' => 'Cuenta Contable', 'id' => 'descripcion_cuenta_ce']); !!}
        </div>
        <div class="row form-group text-center">
                {!! Form::label('CODIGO_IMPUESTO_EXENTO', 'Código Impuesto') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true" id="ci_exento"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::hidden('CODIGO_IMPUESTO_EXENTO', null, ['class' => 'form-control', 'id' => 'codigo_impuesto_exento']); !!}
                {!! Form::text('DESCRIPCION_IMPUESTO_EXENTO', null, ['class' => 'form-control', 'placeholder' => 'Cuenta Contable', 'id' => 'descripcion_codigo_ie']); !!}
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Afecto</h3>
      </div>
      <div class="panel-body">
        <div class="row form-group text-center">
                {!! Form::label('CUENTA_CONTABLE_AFECTO', 'Cuenta Contable') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true" id="cc_afecta"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::hidden('CUENTA_CONTABLE_AFECTO', null, ['class' => 'form-control', 'id' => 'cuenta_contable_afecta']); !!}
                {!! Form::text('DESCRIPCION_CUENTA_AFECTO', null, ['class' => 'form-control', 'placeholder' => 'Cuenta Contable', 'id' => 'descripcion_cuenta_ca']); !!}
        </div>
        <div class="row form-group text-center">
                {!! Form::label('CODIGO_IMPUESTO_AFECTO', 'Código Impuesto') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true" id="ci_afecto"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::hidden('CODIGO_IMPUESTO_AFECTO', null, ['class' => 'form-control', 'id' => 'codigo_impuesto_afecto']); !!}
                {!! Form::text('DESCRIPCION_IMPUESTO_AFECTO', null, ['class' => 'form-control', 'placeholder' => 'Cuenta Contable', 'id' => 'descripcion_codigo_ia']); !!}
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Remanente</h3>
      </div>
      <div class="panel-body">
        <div class="row form-group text-center">
                {!! Form::label('CUENTA_CONTABLE_REMANENTE', 'Cuenta Contable') !!} &nbsp; &nbsp;<a href="#"> <span class="glyphicon glyphicon-import" aria-hidden="true" id="cc_remanente"></span></a>
        </div>
        <div class="row form-group text-center">
                {!! Form::hidden('CUENTA_CONTABLE_REMANENTE', null, ['class' => 'form-control', 'id' => 'cuenta_contable_remanente']); !!}
                {!! Form::text('DESCRIPCION_CUENTA_REMANENTE', null, ['class' => 'form-control', 'placeholder' => 'Cuenta Contable', 'id' => 'descripcion_cuenta_cr']); !!}
        </div>
        <div class="row form-group text-center">
                {!! Form::label('CODIGO_IMPUESTO_REMANENTE', 'Código Impuesto') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true" id="ci_remanente"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::hidden('CODIGO_IMPUESTO_REMANENTE', null, ['class' => 'form-control', 'id' => 'codigo_impuesto_remanente']); !!}
                {!! Form::text('DESCRIPCION_IMPUESTO_REMANENTE', null, ['class' => 'form-control', 'placeholder' => 'Cuenta Contable', 'id' => 'descripcion_codigo_ir']); !!}
        </div>
      </div>
    </div>
  </div>
</div>

<hr>

<div class="row form-group">
  <div class="col-md-2 col-md-offset-1">
        {!! Form::label('ANULADO', 'Anular') !!}
  </div>
  <div class="col-md-4">
      {!! Form::checkbox('ANULADO', 1); !!}
  </div>
</div>

<div class="modal fade" id="myModalCe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cuenta Contable Exenta</h4>
      </div>
      <div class="modal-body">
        <div id="cuenta_exenta"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>
        <button type="button" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" id="ok_exenta"></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModalCa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cuenta Contable Afecta</h4>
      </div>
      <div class="modal-body">
        <div id="cuenta_afecta">

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>
        <button type="button" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" id="ok_afecta"></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModalCr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cuenta Contable Remanente</h4>
      </div>
      <div class="modal-body">
        <div id="cuenta_remanente"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>
        <button type="button" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" id="ok_remanente"></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModalIe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Codigo Impuesto Exento</h4>
      </div>
      <div class="modal-body">
        <div id="impuesto_exento"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>
        <button type="button" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" id="ok_iexento"></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModalIa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Codigo Impuesto Afecto</h4>
      </div>
      <div class="modal-body">
        <div id="impuesto_afecto"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>
        <button type="button" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" id="ok_iafecto"></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModalIr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Codigo Impuesto Remanente</h4>
      </div>
      <div class="modal-body">
        <div id="impuesto_remanente"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>
        <button type="button" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" id="ok_iremanente"></button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
  <script type="text/javascript">
      $(document).ready(function () {

          var exento = $("#gas").prop("checked") ? true : false;
          if (exento === true) {
              $('#Kilometros').show();
          }

          var cantidad = $("#cantidad").prop("checked") ? true : false;
          if (cantidad == true) {
              $('.ph_cantidad').show();
              $('#unidad').show()
          }

          var porcentaje = $("#porcentaje").prop("checked") ? true : false;
          if (porcentaje == true) {
              $('#ph_porcentaje').show();
          }


        $('#gas').click(function () {
          $('#Kilometros').show();
        });

        $('#no_gas').click(function () {
            $('#Kilometros').hide();
        });

        $('#cantidad').click(function () {
          $('.ph_cantidad').show();
          $('#ph_porcentaje').hide();
        })

        $('#porcentaje').click(function () {
          $('#ph_porcentaje').show();
          $('.ph_cantidad').hide();
        })

        $('#no_exento').click(function () {
          $('.div_exento').hide();
          $('#ph_porcentaje').hide();
          $('.ph_cantidad').hide();
        })

        $('#cc_exento').click(function() {
              vurl = '{{ route('cuentaContableExenta')}}'
              vurl = vurl.replace('%7Bid%7D', 1);
              //alert(vurl)
              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {
                      $('#cuenta_exenta').html(data);
                      $('#myModalCe').modal('show')
                  }
              });

            $('#cuentaContableExenta').select2({
                placeholder: 'Seleccione un Proveedor'
            })
          })

          $('#ok_exenta').click(function() {
              var codigo_exenta = $('#cuentaContableExenta').val()
              var descripcion_exenta = $('#cuentaContableExenta option:selected').text()
              $('#cuenta_contable_exenta').val(codigo_exenta)
              $('#descripcion_cuenta_ce').val(descripcion_exenta)
              $('#myModalCe').modal('hide')
          })

          $('#cc_afecta').click(function() {
              vurl = '{{ route('cuentaContableAfecta')}}'
              vurl = vurl.replace('%7Bid%7D', 1);
              //alert(vurl)
              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {
                      $('#cuenta_afecta').html(data);
                      $('#myModalCa').modal('show')
                  }
              });
          })

          $('#ok_afecta').click(function() {
              var codigo_afecta = $('#cuentaContableAfecta').val()
              var descripcion_afecta = $('#cuentaContableAfecta option:selected').text()
              $('#cuenta_contable_afecta').val(codigo_afecta)
              $('#descripcion_cuenta_ca').val(descripcion_afecta)
              $('#myModalCa').modal('hide')
          })

          $('#cc_remanente').click(function() {
              vurl = '{{ route('cuentaContableRemanente')}}'
              vurl = vurl.replace('%7Bid%7D', 1);
              //alert(vurl)
              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {
                      $('#cuenta_remanente').html(data);
                      $('#myModalCr').modal('show')
                  }
              });
          })

          $('#ok_remanente').click(function() {
              var codigo_remanente = $('#cuentaContableRemanente').val()
              var descripcion_remanente = $('#cuentaContableRemanente option:selected').text()
              $('#cuenta_contable_remanente').val(codigo_remanente)
              $('#descripcion_cuenta_cr').val(descripcion_remanente)
              $('#myModalCr').modal('hide')
          })



          $('#ci_exento').click(function() {
              //alert('que pasa')
              vurl = '{{ route('codigoImpuestoExento')}}'
              vurl = vurl.replace('%7Bid%7D', 5);
              //alert(vurl)
              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {
                      $('#impuesto_exento').html(data);
                      $('#myModalIe').modal('show')
                  }
              });
          })

          $('#ok_iexento').click(function() {
              var codigo_impuesto_exento = $('#codigoImpuestoExento').val()
              var descripcion_codigo_ie = $('#codigoImpuestoExento option:selected').text()
              $('#codigo_impuesto_exento').val(codigo_impuesto_exento)
              $('#descripcion_codigo_ie').val(descripcion_codigo_ie)
              $('#myModalIe').modal('hide')
          })

          $('#ci_afecto').click(function() {
              //alert('que pasa')
              vurl = '{{ route('codigoImpuestoAfecto')}}'
              vurl = vurl.replace('%7Bid%7D', 5);
              //alert(vurl)
              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {
                      $('#impuesto_afecto').html(data);
                      $('#myModalIa').modal('show')
                  }
              });
          })

          $('#ok_iafecto').click(function() {

              var codigo_impuesto_afecto = $('#codigoImpuestoAfecto').val()
              var descripcion_codigo_ia = $('#codigoImpuestoAfecto option:selected').text()
              $('#codigo_impuesto_afecto').val(codigo_impuesto_afecto)
              $('#descripcion_codigo_ia').val(descripcion_codigo_ia)
              $('#myModalIa').modal('hide')
          })

          $('#ci_remanente').click(function() {
              //alert('que pasa')
              vurl = '{{ route('codigoImpuestoRemanente')}}'
              vurl = vurl.replace('%7Bid%7D', 5);

              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {
                      $('#impuesto_remanente').html(data);
                      $('#myModalIr').modal('show')
                  }
              });
          })

          $('#ok_iremanente').click(function() {
              //alert('sender')
              var codigo_impuesto_remanente = $('#codigoImpuestoRemanente').val()
              var descripcion_codigo_ir = $('#codigoImpuestoRemanente option:selected').text()
              $('#codigo_impuesto_remanente').val(codigo_impuesto_remanente)
              $('#descripcion_codigo_ir').val(descripcion_codigo_ir)
              $('#myModalIr').modal('hide')
          })

      });
  </script>
@endpush

@if (isset($tipoGasto_id))
    <input id="TIPOGASTO_ID" name="TIPOGASTO_ID" type="hidden" value="{{ $tipoGasto_id }}">
@endif
{!! Form::hidden('CAUSAEXENCION_ID', 1, ['class' => 'form-control']); !!}

<div class="row form-group">
  <div class="col-md-2 col-md-offset-1">
        {!! Form::label('DESCRIPCION', 'Descripción') !!}
  </div>
  <div class="col-md-3">
      {!! Form::text('DESCRIPCION', null, ['class' => 'form-control', 'placeholder' => 'Descripción Gasto']); !!}
  </div>
</div>
<div class="row form-group">

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
      @if(isset($subcategoriaTipoGastos->MONTO_A_APLICAR_CANTIDAD))
        {!! Form::text('MONTO_A_APLICAR_CANTIDAD', $subcategoriaTipoGastos->MONTO_A_APLICAR_CANTIDAD, ['class' => 'form-control', 'placeholder' => 'Cantidad']); !!}
      @else
        {!! Form::text('MONTO_A_APLICAR_CANTIDAD', 0, ['class' => 'form-control', 'placeholder' => 'Cantidad']); !!}
      @endif
  </div>
    <div class="col-md-3 ph_cantidad" style="display: none" >
        {!! Form::select('UNIDAD_MEDIDA_ID', $tipoAsignacion, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Tipo de Asignación', 'id' => 'tipoAsignacion']); !!}
    </div>

</div>

<div class="row form-group">
  <div class="col-md-2 col-md-offset-1 div_exento" style="display: none">
      {!! Form::radio('CAUSAEXENCION_ID', 3, false, ['id' => 'porcentaje']); !!}  PORCENTAJE
  </div>

  <div class="col-md-2" style="display: none" id="ph_porcentaje">
      @if(isset($subcategoriaTipoGastos->MONTO_A_APLICAR_PORCENTAJE))
        {!! Form::text('MONTO_A_APLICAR_PORCENTAJE', $subcategoriaTipoGastos->MONTO_A_APLICAR_PORCENTAJE, ['class' => 'form-control', 'placeholder' => 'Porcentaje']); !!}
      @else
        {!! Form::text('MONTO_A_APLICAR_PORCENTAJE', 0, ['class' => 'form-control', 'placeholder' => 'Porcentaje']); !!}
      @endif
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



@push('scripts')
  <script type="text/javascript">
      $(document).ready(function () {

          var exento = $("#exento").prop("checked") ? true : false;
          if (exento === true) {
              $('.div_exento').show();
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


        $('#exento').click(function () {
          $('.div_exento').show();
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
              alert(vurl)
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
              alert(vurl)
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
              alert(vurl)
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
              alert('que pasa')
              vurl = '{{ route('codigoImpuestoExento')}}'
              vurl = vurl.replace('%7Bid%7D', 5);
              alert(vurl)
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
              alert('que pasa')
              vurl = '{{ route('codigoImpuestoAfecto')}}'
              vurl = vurl.replace('%7Bid%7D', 5);
              alert(vurl)
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
              alert('que pasa')
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
              alert('sender')
              var codigo_impuesto_remanente = $('#codigoImpuestoRemanente').val()
              var descripcion_codigo_ir = $('#codigoImpuestoRemanente option:selected').text()
              $('#codigo_impuesto_remanente').val(codigo_impuesto_remanente)
              $('#descripcion_codigo_ir').val(descripcion_codigo_ir)
              $('#myModalIr').modal('hide')
          })

      });
  </script>
@endpush

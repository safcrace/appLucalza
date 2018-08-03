{{--  @if (isset($empresa_id))  --}}
    <input id="EMPRESA_ID" name="EMPRESA_ID" type="hidden" value="{{ $empresa_id }}">
{{--  @endif  --}}
{!! Form::hidden('CAUSAEXENCION_ID', 1, ['class' => 'form-control']); !!}
<div class="row form-group">
    <div class="col-md-2 col-md-offset-1">
        {!! Form::label('GRUPO_ID', 'Grupo') !!}
    </div>
    <div class="col-md-3">
        @if(isset($tipoGasto->GRUPOTIPOGASTO_ID))
            {!! Form::select('GRUPO_ID', $grupo, $tipoGasto->GRUPOTIPOGASTO_ID, ['class' => 'form-control', 'placeholder' => 'Seleccione un Grupo', 'id' => 'grupoTipo']); !!}
        @else
            {!! Form::select('GRUPO_ID', $grupo, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un Grupo', 'id' => 'grupoTipo']); !!}
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
            {!! Form::label('CONTROL_DEPRECIACION', 'Depreciación') !!}
        </div>
        <div class="col-md-1">
            {!! Form::radio('CONTROL_DEPRECIACION', 1, false, ['id' => 'depre']); !!}  SI
        </div>
        <div class="col-md-1">
            {!! Form::radio('CONTROL_DEPRECIACION', 0, true, ['id' => 'no_depre']); !!}  NO
        </div>

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

    {{--  <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('OPCIONCOMBUSTIBLE_ID', 'Control Combustible') !!}
        </div>
        <div class="col-md-1">
            {!! Form::radio('OPCIONCOMBUSTIBLE_ID', 1, false, ['id' => 'gas']); !!}  SI
        </div>
        <div class="col-md-1">
            {!! Form::radio('OPCIONCOMBUSTIBLE_ID', 2, true, ['id' => 'no_gas']); !!}  NO
        </div>
    </div>  --}}

<div class="row form-group" id="Kilometros" style="display: none">
    <div class="col-md-2 col-md-offset-6">
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
                {!! Form::label('CUENTA_CONTABLE_EXENTO', 'Cuenta Contable') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true" id="cc_exento" style="cursor: pointer"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::hidden('CUENTA_CONTABLE_EXENTO', null, ['class' => 'form-control', 'id' => 'cuenta_contable_exenta']); !!}
                {!! Form::text('DESCRIPCION_CUENTA_EXENTO', null, ['class' => 'form-control descripcion_cuenta_ce text-center', 'placeholder' => 'Cuenta Contable', 'disabled']); !!}
                @if(isset($tipoGasto->DESCRIPCION_CUENTA_EXENTO))
                  {!! Form::hidden('CUENTA_EXENTO', $tipoGasto->DESCRIPCION_CUENTA_EXENTO, ['class' => 'form-control descripcion_cuenta_ce', 'placeholder' => 'Cuenta Contable']); !!}
                @else
                  {!! Form::hidden('CUENTA_EXENTO', null, ['class' => 'form-control descripcion_cuenta_ce', 'placeholder' => 'Cuenta Contable']); !!}
                @endif
        </div>
        <div class="row form-group text-center">
                {!! Form::label('CODIGO_IMPUESTO_EXENTO', 'Código Impuesto') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true" id="ci_exento" style="cursor: pointer"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::hidden('CODIGO_IMPUESTO_EXENTO', null, ['class' => 'form-control', 'id' => 'codigo_impuesto_exento']); !!}
                {!! Form::text('DESCRIPCION_IMPUESTO_EXENTO', null, ['class' => 'form-control descripcion_codigo_ie text-center', 'placeholder' => 'Codigo Impuesto', 'disabled']); !!}
                @if(isset($tipoGasto->DESCRIPCION_IMPUESTO_EXENTO))
                  {!! Form::hidden('IMPUESTO_EXENTO', $tipoGasto->DESCRIPCION_IMPUESTO_EXENTO, ['class' => 'form-control descripcion_codigo_ie', 'placeholder' => 'Cuenta Contable']); !!}
                @else
                  {!! Form::hidden('IMPUESTO_EXENTO', null, ['class' => 'form-control descripcion_codigo_ie', 'placeholder' => 'Cuenta Contable']); !!}
                @endif
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
                {!! Form::label('CUENTA_CONTABLE_AFECTO', 'Cuenta Contable') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true" id="cc_afecta" style="cursor: pointer"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::hidden('CUENTA_CONTABLE_AFECTO', null, ['class' => 'form-control', 'id' => 'cuenta_contable_afecta']); !!}
                {!! Form::text('DESCRIPCION_CUENTA_AFECTO', null, ['class' => 'form-control descripcion_cuenta_ca text-center', 'placeholder' => 'Cuenta Contable', 'disabled']); !!}
                @if(isset($tipoGasto->DESCRIPCION_CUENTA_AFECTO))
                  {!! Form::hidden('CUENTA_AFECTO', $tipoGasto->DESCRIPCION_CUENTA_AFECTO, ['class' => 'form-control descripcion_cuenta_ca text-center', 'placeholder' => 'Cuenta Contable']); !!}
                @else
                  {!! Form::hidden('CUENTA_AFECTO', null, ['class' => 'form-control descripcion_cuenta_ca', 'placeholder' => 'Cuenta Contable']); !!}
                @endif
        </div>
        <div class="row form-group text-center">
                {!! Form::label('CODIGO_IMPUESTO_AFECTO', 'Código Impuesto') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true" id="ci_afecto" style="cursor: pointer"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::hidden('CODIGO_IMPUESTO_AFECTO', null, ['class' => 'form-control', 'id' => 'codigo_impuesto_afecto']); !!}
                {!! Form::text('DESCRIPCION_IMPUESTO_AFECTO', null, ['class' => 'form-control descripcion_codigo_ia text-center', 'placeholder' => 'Cuenta Contable', 'disabled']); !!}
                @if(isset($tipoGasto->DESCRIPCION_IMPUESTO_AFECTO))
                  {!! Form::hidden('IMPUESTO_AFECTO', $tipoGasto->DESCRIPCION_IMPUESTO_AFECTO, ['class' => 'form-control descripcion_codigo_ia', 'placeholder' => 'Cuenta Contable']); !!}
                @else
                  {!! Form::hidden('IMPUESTO_AFECTO', null, ['class' => 'form-control descripcion_codigo_ia', 'placeholder' => 'Cuenta Contable']); !!}
                @endif
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
                {!! Form::label('CUENTA_CONTABLE_REMANENTE', 'Cuenta Contable') !!} &nbsp; &nbsp;<span class="glyphicon glyphicon-import" aria-hidden="true" id="cc_remanente" style="cursor: pointer"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::hidden('CUENTA_CONTABLE_REMANENTE', null, ['class' => 'form-control', 'id' => 'cuenta_contable_remanente']); !!}
                {!! Form::text('DESCRIPCION_CUENTA_REMANENTE', null, ['class' => 'form-control descripcion_cuenta_cr text-center', 'placeholder' => 'Cuenta Contable', 'disabled']); !!}
                @if(isset($tipoGasto->DESCRIPCION_CUENTA_REMANENTE))
                  {!! Form::hidden('CUENTA_REMANENTE', $tipoGasto->DESCRIPCION_CUENTA_REMANENTE, ['class' => 'form-control descripcion_cuenta_cr', 'placeholder' => 'Cuenta Contable']); !!}
                @else
                  {!! Form::hidden('CUENTA_REMANENTE', null, ['class' => 'form-control descripcion_cuenta_cr', 'placeholder' => 'Cuenta Contable']); !!}
                @endif
        </div>
        <div class="row form-group text-center">
                {!! Form::label('CODIGO_IMPUESTO_REMANENTE', 'Código Impuesto') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true" id="ci_remanente" style="cursor: pointer"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::hidden('CODIGO_IMPUESTO_REMANENTE', null, ['class' => 'form-control', 'id' => 'codigo_impuesto_remanente']); !!}
                {!! Form::text('DESCRIPCION_IMPUESTO_REMANENTE', null, ['class' => 'form-control descripcion_codigo_ir text-center', 'placeholder' => 'Cuenta Contable', 'disabled']); !!}
                @if(isset($tipoGasto->DESCRIPCION_IMPUESTO_REMANENTE))
                  {!! Form::hidden('IMPUESTO_REMANENTE', $tipoGasto->DESCRIPCION_IMPUESTO_REMANENTE, ['class' => 'form-control descripcion_codigo_ir', 'placeholder' => 'Cuenta Contable']); !!}
                @else
                  {!! Form::hidden('IMPUESTO_REMANENTE', null, ['class' => 'form-control descripcion_codigo_ir', 'placeholder' => 'Cuenta Contable']); !!}
                @endif
        </div>
      </div>
    </div>
  </div>
</div>

<hr>

{{-- <div class="row form-group">
  <div class="col-md-2 col-md-offset-1">
        {!! Form::label('ANULADO', 'Anular') !!}
  </div>
  <div class="col-md-4">
      {!! Form::checkbox('ANULADO', 1); !!}
  </div>
</div> --}}

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

<div class="loader-modal-container" id="loader">
  <img src="{{ asset('images/loader.gif') }}" alt="Loader Container" class="loader-info">  
</div>

@push('scripts')
  <script type="text/javascript">
      $(document).ready(function () {

          $('#grupoTipo').select2({
            placeholder: 'Seleccione una opción'
        })

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
              $('#loader').show()            
              var criterio = $('#EMPRESA_ID').val() + '-' + 1              
              vurl = '{{ route('cuentaContableExenta')}}'
              vurl = vurl.replace('%7Bid%7D', criterio);
              //alert(vurl)
              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {                    
                      $('#cuenta_exenta').html(data);
                      $('#loader').hide()
                      $('#myModalCe').modal('show')
                  }
              });
          
              $('#cuentaContableExenta').on('click', '#cc_exento', function() {                
                var senderito = $('#cuentaContableExenta').find(':selected').attr('data-postable')
                console.log(senderito)
                      //var descripcion_exenta = $('#cuentaContableExenta option:selected').text()
                      //var senderito = $('#cuentaContableExenta').find(':selected').attr('data-data-postable')
                      //console.log(descripcion_exenta)

                      //var descripcion_proveedorSap = $('#codigoProveedorSap option:selected').text()
                //$('#descripcion_proveedorsap').val(descripcion_proveedorSap)
              })     

           $('#cuentaContableExenta').select2({
                placeholder: 'Seleccione un Proveedor'
            })
          })

          $('#ok_exenta').click(function() {
              var codigo_exenta = $('#cuentaContableExenta').val()
              var descripcion_exenta = $('#cuentaContableExenta option:selected').text()            
              //var $(this).find(':selected').data('id')
              var postable = $('#cuentaContableExenta').find(':selected').attr('data-postable')
              if(postable == 'Y') {
                  $('#cuenta_contable_exenta').val(codigo_exenta)
                  $('.descripcion_cuenta_ce').val(descripcion_exenta)
                  $('#myModalCe').modal('hide')                
              } else {
                alert('No puede Seleccionar una Cuenta Padre')
              }
          })

          $('#cc_afecta').click(function() {
              $('#loader').show()
              var criterio = $('#EMPRESA_ID').val() + '-' + 1
              vurl = '{{ route('cuentaContableAfecta')}}'
              vurl = vurl.replace('%7Bid%7D', criterio);
              //alert(vurl)
              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {                    
                      $('#cuenta_afecta').html(data);
                      $('#loader').hide()
                      $('#myModalCa').modal('show')
                  }
              });
          })

          $('#ok_afecta').click(function() {
              var codigo_afecta = $('#cuentaContableAfecta').val()
              var descripcion_afecta = $('#cuentaContableAfecta option:selected').text()
              var postable = $('#cuentaContableAfecta').find(':selected').attr('data-postable')              
              if(postable == 'Y') {                  
                  $('#cuenta_contable_afecta').val(codigo_afecta)
                  $('.descripcion_cuenta_ca').val(descripcion_afecta)
                  $('#myModalCa').modal('hide')                
              } else {
                alert('No puede Seleccionar una Cuenta Padre')
              }              
          })

          $('#cc_remanente').click(function() {
              $('#loader').show()
              var criterio = $('#EMPRESA_ID').val() + '-' + 1
              vurl = '{{ route('cuentaContableRemanente')}}'
              vurl = vurl.replace('%7Bid%7D', criterio);
              //alert(vurl)
              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {
                      $('#cuenta_remanente').html(data);
                      $('#loader').hide()
                      $('#myModalCr').modal('show')
                  }
              });
          })

          $('#ok_remanente').click(function() {
              var codigo_remanente = $('#cuentaContableRemanente').val()
              var descripcion_remanente = $('#cuentaContableRemanente option:selected').text()
              var postable = $('#cuentaContableRemanente').find(':selected').attr('data-postable')
              if(postable == 'Y') {                  
                  $('#cuenta_contable_remanente').val(codigo_remanente)
                  $('.descripcion_cuenta_cr').val(descripcion_remanente)
                  $('#myModalCr').modal('hide')
              } else {
                alert('No puede Seleccionar una Cuenta Padre')
              }              
          })



          $('#ci_exento').click(function() {
              //alert('que pasa')
              $('#loader').show()
              var criterio = $('#EMPRESA_ID').val() + '-' + 5
              vurl = '{{ route('codigoImpuestoExento')}}'
              vurl = vurl.replace('%7Bid%7D', criterio);
              //alert(vurl)
              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {
                      $('#impuesto_exento').html(data);
                      $('#loader').hide()
                      $('#myModalIe').modal('show')
                  }
              });
          })

          $('#ok_iexento').click(function() {
              var codigo_impuesto_exento = $('#codigoImpuestoExento').val()
              var descripcion_codigo_ie = $('#codigoImpuestoExento option:selected').text()
              $('#codigo_impuesto_exento').val(codigo_impuesto_exento)
              $('.descripcion_codigo_ie').val(descripcion_codigo_ie)
              $('#myModalIe').modal('hide')
          })

          $('#ci_afecto').click(function() {
              //alert('que pasa')
              $('#loader').show()
              var criterio = $('#EMPRESA_ID').val() + '-' + 5
              vurl = '{{ route('codigoImpuestoAfecto')}}'
              vurl = vurl.replace('%7Bid%7D', criterio);
              //alert(vurl)
              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {
                      $('#impuesto_afecto').html(data);
                      $('#loader').hide()
                      $('#myModalIa').modal('show')
                  }
              });
          })

          $('#ok_iafecto').click(function() {

              var codigo_impuesto_afecto = $('#codigoImpuestoAfecto').val()
              var descripcion_codigo_ia = $('#codigoImpuestoAfecto option:selected').text()
              $('#codigo_impuesto_afecto').val(codigo_impuesto_afecto)
              $('.descripcion_codigo_ia').val(descripcion_codigo_ia)
              $('#myModalIa').modal('hide')
          })

          $('#ci_remanente').click(function() {
              //alert('que pasa')
              $('#loader').show()
              var criterio = $('#EMPRESA_ID').val() + '-' + 5
              vurl = '{{ route('codigoImpuestoRemanente')}}'
              vurl = vurl.replace('%7Bid%7D', criterio);

              $.ajax({
                  type: 'get',
                  url: vurl,
                  success: function (data) {
                      $('#impuesto_remanente').html(data);
                      $('#loader').hide()
                      $('#myModalIr').modal('show')
                  }
              });
          })

          $('#ok_iremanente').click(function() {
              //alert('sender')
              var codigo_impuesto_remanente = $('#codigoImpuestoRemanente').val()
              var descripcion_codigo_ir = $('#codigoImpuestoRemanente option:selected').text()
              $('#codigo_impuesto_remanente').val(codigo_impuesto_remanente)
              $('.descripcion_codigo_ir').val(descripcion_codigo_ir)
              $('#myModalIr').modal('hide')
          })

      });
  </script>
@endpush

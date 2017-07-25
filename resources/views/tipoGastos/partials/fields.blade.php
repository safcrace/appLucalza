@if (isset($empresa_id))
    <input id="EMPRESA_ID" name="EMPRESA_ID" type="hidden" value="{{ $empresa_id }}">
@endif

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
        {!! Form::label('EXENTO', 'Exento') !!}
  </div>
  <div class="col-md-1">
      {!! Form::radio('EXENTO', 1, false, ['id' => 'exento']); !!}  SI
  </div>
  <div class="col-md-2 div_exento" style="display: none">
      {!! Form::radio('CAUSAEXENCION_ID', 2, false, ['id' => 'cantidad']); !!}  CANTIDAD
  </div>
  <div class="col-md-2" style="display: none" id="ph_cantidad">
      {!! Form::text('MONTO_A_APLICAR_CANTIDAD', null, ['class' => 'form-control', 'placeholder' => 'Cantidad']); !!}
  </div>
  <div class="col-md-2" style="display: none" id="unidad">
      {!! Form::text('UNIDAD_MEDIDA', null, ['class' => 'form-control', 'placeholder' => 'Unidad de Medida']); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-1  col-md-offset-3">
      {!! Form::radio('EXENTO', 0, false, ['id' => 'no_exento']); !!}  NO
  </div>
  <div class="col-md-2 div_exento" style="display: none">
      {!! Form::radio('CAUSAEXENCION_ID', 3, false, ['id' => 'porcentaje']); !!}  PORCENTAJE
  </div>

  <div class="col-md-2" style="display: none" id="ph_porcentaje">
      {!! Form::text('MONTO_A_APLICAR_PORCENTAJE', null, ['class' => 'form-control', 'placeholder' => 'Porcentaje']); !!}
  </div>
</div>

<hr>

<div class="row">
  <div class="col-md-3 col-sm-offset-2">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Exento</h3>
      </div>
      <div class="panel-body">
        <div class="row form-group text-center">
                {!! Form::label('CUENTA_CONTABLE_EXENTO', 'Cuenta Contable') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::text('CUENTA_CONTABLE_EXENTO', null, ['class' => 'form-control', 'placeholder' => 'Cuenta Contable']); !!}
        </div>
        <div class="row form-group text-center">
                {!! Form::label('CODIGO_IMPUESTO_EXENTO', 'Código Impuesto') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::text('CODIGO_IMPUESTO_EXENTO', null, ['class' => 'form-control', 'placeholder' => 'Código Impuesto']); !!}
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Afecto</h3>
      </div>
      <div class="panel-body">
        <div class="row form-group text-center">
                {!! Form::label('CUENTA_CONTABLE_AFECTO', 'Cuenta Contable') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::text('CUENTA_CONTABLE_AFECTO', null, ['class' => 'form-control', 'placeholder' => 'Cuenta Contable']); !!}
        </div>
        <div class="row form-group text-center">
                {!! Form::label('CODIGO_IMPUESTO_AFECTO', 'Código Impuesto') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::text('CODIGO_IMPUESTO_AFECTO', null, ['class' => 'form-control', 'placeholder' => 'Código Impuesto']); !!}
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Remanente</h3>
      </div>
      <div class="panel-body">
        <div class="row form-group text-center">
                {!! Form::label('CUENTA_CONTABLE_REMANENTE', 'Cuenta Contable') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::text('CUENTA_CONTABLE_REMANENTE', null, ['class' => 'form-control', 'placeholder' => 'Cuenta Contable']); !!}
        </div>
        <div class="row form-group text-center">
                {!! Form::label('CODIGO_IMPUESTO_REMANENTE', 'Código Impuesto') !!} &nbsp; &nbsp; <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
        </div>
        <div class="row form-group text-center">
                {!! Form::text('CODIGO_IMPUESTO_REMANENTE', null, ['class' => 'form-control', 'placeholder' => 'Código Impuesto']); !!}
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

@push('scripts')
  <script type="text/javascript">
      $(document).ready(function () {

          var exento = $("#exento").prop("checked") ? true : false;
          if (exento === true) {
              $('.div_exento').show();
          }

          var cantidad = $("#cantidad").prop("checked") ? true : false;
          if (cantidad == true) {
              $('#ph_cantidad').show();
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
          $('#ph_cantidad').show();
          $('#unidad').show()
          $('#ph_porcentaje').hide();
        })

        $('#porcentaje').click(function () {
          $('#ph_porcentaje').show();
          $('#ph_cantidad').hide();
          $('#unidad').hide()
        })

        $('#no_exento').click(function () {
          $('.div_exento').hide();
          $('#ph_porcentaje').hide();
          $('#ph_cantidad').hide();
          $('#unidad').hide()
        })
      });
  </script>
@endpush

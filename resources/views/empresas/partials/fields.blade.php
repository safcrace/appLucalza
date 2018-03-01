<div class="panel panel-primary">
  <div class="panel-heading">Datos Empresa</div>

  <div class="panel-body">

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('CLAVE', 'Código Empresa') !!}
      </div>
      <div class="col-md-2">
          {!! Form::text('CLAVE', null, ['class' => 'form-control', 'placeholder' => 'Código de Empresa']); !!}
      </div>
      <div class="col-md-2 col-md-offset-2">
        {!! Form::label('MONEDA_LOCAL', 'Moneda Local') !!}
      </div>
      <div class="col-md-1">
        {!! Form::text('MONEDA_LOCAL', null, ['class' => 'form-control']); !!}
      </div>  
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('DESCRIPCION', 'Nombre Empresa') !!}
      </div>
      <div class="col-md-4">
          {!! Form::text('DESCRIPCION', null, ['class' => 'form-control', 'placeholder' => 'Nombre de Empresa']); !!}
      </div>

      <div class="col-md-2">
            {!! Form::label('MONEDA_SYS', 'Moneda Extranjera') !!} {{--ANTERIORMENTE MONEDA_ID --}}
      </div>
      <div class="col-md-1">
        {!! Form::text('MONEDA_SYS', null, ['class' => 'form-control']); !!}
          {{--  {!! Form::select('MONEDA_ID', $moneda, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Moneda']); !!}  --}}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-3 col-md-offset-1">
        {!! Form::label('DESCRIPCION', 'Días de atraso Facturación Rutas') !!}
      </div>
      <div class="col-md-1">
        {!! Form::text('TIEMPOATRASO_RUTAS', null, ['class' => 'form-control', 'placeholder' => '# días']); !!}
      </div>      
      <div class="col-md-2">
        {!! Form::label('IMPUESTO', 'Valor Impuesto') !!}
      </div>
      <div class="col-md-2">
        {!! Form::text('IMPUESTO', null, ['class' => 'form-control', 'placeholder' => 'Valor Impuesto']); !!} 
      </div>
      <div class="col-md-1">
        %
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-3 col-md-offset-1">
        {!! Form::label('DESCRIPCION', 'Días de atraso Facturación Gastos') !!}
      </div>
      <div class="col-md-1">
        {!! Form::text('TIEMPOATRASO_OTROSGASTOS', null, ['class' => 'form-control', 'placeholder' => '# días']); !!}
      </div>
      <div class="col-md-2">
            {!! Form::label('ANULADO', 'Estatus') !!}
      </div>
      @if(isset($empresa->ANULADO))
        @if($empresa->ANULADO == 1)
          <div class="col-md-1">          
            <input name="ANULADO" type="radio" value="0" >
            Activo
          </div>
          <div class="col-md-2">
              <input name="ANULADO"  type="radio" value="1" checked=true>  Inactivo
          </div>
        @else
          <div class="col-md-1">          
            <input name="ANULADO" type="radio" value="0" checked=true>
            Activo
          </div>
          <div class="col-md-2">
              <input name="ANULADO"  type="radio" value="1" >  Inactivo
          </div>
        @endif
      @else
        <div class="col-md-1">          
          <input name="ANULADO" type="radio" value="0" checked=true >
          Activo
        </div>
        <div class="col-md-2">
            <input name="ANULADO"  type="radio" value="1" >  Inactivo
        </div>
      @endif
    </div>


  </div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading">Datos SAP</div>

  <div class="panel-body">

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('LICENSESERVER', 'Servidor de Licencias') !!}
      </div>
      <div class="col-md-2">
          {!! Form::text('LICENSESERVER', null, ['class' => 'form-control', 'placeholder' => 'Licencia de Servidor SAP']); !!}
      </div>
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('DBSAP', 'Base de Datos SAP') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('DBSAP', null, ['class' => 'form-control', 'placeholder' => 'Base Datos SAP']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('USERSAP', 'Usuario SAP') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('USERSAP', null, ['class' => 'form-control', 'placeholder' => 'Usuario SAP']); !!}
      </div>
      <div class="col-md-2">
            {!! Form::label('FILAS_NOTA_CREDITO', 'Remanente en Nota de Crédito') !!}
      </div>
      <div class="col-md-1">
          @if(isset($empresa->FILAS_NOTA_CREDITO))
            @if($empresa->FILAS_NOTA_CREDITO == 1)
                <input name="FILAS_NOTA_CREDITO" type="radio" value="1" checked>  Si
            @else
                <input name="FILAS_NOTA_CREDITO" type="radio" value="1">  Si
            @endif          
          @else
          <input name="FILAS_NOTA_CREDITO" type="radio" value="1">  Si
          @endif

      </div>
      <div class="col-md-2">
          @if(isset($empresa->FILAS_NOTA_CREDITO))
            @if($empresa->FILAS_NOTA_CREDITO == 0)
                <input name="FILAS_NOTA_CREDITO" type="radio" value="0" checked>  No
            @else
                <input name="FILAS_NOTA_CREDITO" type="radio" value="0">  No
            @endif  
          @else
            <input name="FILAS_NOTA_CREDITO" type="radio" value="0">  No        
          @endif          
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('PASSSAP', 'Contraseña SAP') !!}
      </div>
      <div class="col-md-3">
          {!! Form::password('PASSSAP', ['class' => 'form-control awesome', 'placeholder' => 'Contraseña SAP']); !!}
      </div>
      <div class="col-md-1">
        <span class="glyphicon glyphicon-eye-open" aria-hidden="true" style="color: black; cursor: pointer; font-size: 22px" data-toggle="tooltip" data-placement="top" title="Cambiar Vista" id="verPasswordSap"></span>
    </div>
      
    </div>


  </div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading">Datos SQL</div>

  <div class="panel-body">

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('USERSQL', 'Usuario SQL') !!}
      </div>
      <div class="col-md-2">
          {!! Form::text('USERSQL', null, ['class' => 'form-control', 'placeholder' => 'Usuario SQL']); !!}
      </div>
      <div class="col-md-2 col-md-offset-1">
        {!! Form::label('ID_DATASERVERTYPE', 'SAP DB Type') !!}
      </div>
      <div class="col-md-3">
        {!! Form::select('ID_DATASERVERTYPE', $sapDbType, 6, ['class' => 'form-control', 'placeholder' => 'Seleccione SAP DB Type']); !!}
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('PASSSQL', 'Contraseña SQL') !!}
      </div>
      <div class="col-md-3">
          {!! Form::password('PASSSQL', ['class' => 'form-control awesome', 'placeholder' => 'Contraseña SQL']); !!}
      </div>
      <div class="col-md-1">
        <span class="glyphicon glyphicon-eye-open" aria-hidden="true" style="color: black; cursor: pointer; font-size: 22px" data-toggle="tooltip" data-placement="top" title="Cambiar Vista" id="verPasswordSql"></span>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('SERVIDORSQL', 'Servidor SQL') !!}
      </div>
      <div class="col-md-3">
          {!! Form::text('SERVIDORSQL', null, ['class' => 'form-control', 'placeholder' => 'Servidor SQL']); !!}
      </div>
    </div>


  </div>
</div>

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {       
        $('#verPasswordSap').click(function() {          
            tipoInput = $('#PASSSAP').attr('type')           
            if (tipoInput == 'password') {
              $('#PASSSAP').attr("type", 'text')
            } else {
              $('#PASSSAP').attr("type", 'password')
            }            
        })
        $('#verPasswordSql').click(function() {          
            tipoInputTwo = $('#PASSSQL').attr('type')            
            if (tipoInputTwo == 'password') {
              $('#PASSSQL').attr("type", 'text')
            } else {
              $('#PASSSQL').attr("type", 'password')
            }            
        })
        
        $('#MONEDA_LOCAL').focus(function() {
          alert('Este campo es únicamente Administrado por el Sistema')
          $('#CLAVE').focus()
        })

        $('#MONEDA_SYS').focus(function() {
          alert('Este campo es únicamente Administrado por el Sistema')
          $('#CLAVE').focus()
        })
    });
</script>
@endpush
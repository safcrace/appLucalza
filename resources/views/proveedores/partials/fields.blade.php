@if (isset($empresa_id))
    <input id="EMPRESA_ID" name="EMPRESA_ID" type="hidden" value="{{ $empresa_id }}">
@endif


<div class="row form-group">
  <div class="col-md-2 col-md-offset-1">
    {!! Form::label('MONEDA_ID', 'Moneda') !!}
  </div>
  {{--  @if (isset($proveedor->MONEDA_ID))
    @if($proveedor->MONEDA_ID == $moneda->ID)      
      <div class="col-md-2 ">
        {!! Form::radio('MONEDA_ID', $moneda->ID, true); !!}  {{ $moneda->DESCRIPCION }}
      </div>
      <div class="col-md-2">
        {!! Form::radio('MONEDA_ID', 1, false); !!}  DOLAR
      </div>      
    @else      
      <div class="col-md-2 ">
        {!! Form::radio('MONEDA_ID', $moneda->ID, true); !!}  {{ $moneda->DESCRIPCION }}
      </div>
      <div class="col-md-2">
        {!! Form::radio('MONEDA_ID', 1, false); !!}  DOLAR
      </div>
    @endif
  @else  --}}      
    <div class="col-md-2 ">
      {!! Form::radio('MONEDA_ID', $monedaEmpresa->MONEDA_LOCAL, true); !!}  {{ $monedaEmpresa->MONEDA_LOCAL }}
    </div>
    <div class="col-md-2">
      {!! Form::radio('MONEDA_ID', $monedaEmpresa->MONEDA_SYS, false); !!}  {{ $monedaEmpresa->MONEDA_SYS }}
    </div>
  {{--  @endif  --}}
</div>

<div class="row form-group">
  <div class="col-md-2 col-md-offset-1">
        {!! Form::label('IDENTIFICADOR_TRIBUTARIO', 'Identificador Tributario') !!}
  </div>
  <div class="col-md-3">
      {!! Form::text('IDENTIFICADOR_TRIBUTARIO', null, ['class' => 'form-control', 'placeholder' => 'Identificador Tributario Proveedor', 'id' => 'identificador_tributario']); !!}
  </div>
</div>

@if (auth()->user()->hasRole('superAdmin', 'contabilidad'))
  <div class="row form-group">
    <div class="col-md-2 col-md-offset-1">
      {!! Form::label('TIPOPROVEDOR_ID', 'Tipo Proveedor') !!}
    </div>
    <div class="col-md-3">
      @if(isset($proveedor->TIPOPROVEEDOR_ID))
        {!! Form::select('TIPOPROVEEDOR_ID', $tipoProveedor,$proveedor->TIPOPROVEEDOR_ID, ['class' => 'form-control', 'placeholder' => 'Seleccione Tipo Proveedor', 'id' => 'tipoproveedor_id']); !!}
      @else
        {!! Form::select('TIPOPROVEEDOR_ID', $tipoProveedor, 1, ['class' => 'form-control', 'placeholder' => 'Seleccione Tipo Proveedor', 'id' => 'tipoproveedor_id']); !!}
      @endif 
    </div>
  </div>
@endif

<div class="row form-group">
  <div class="col-md-2 col-md-offset-1">
        {!! Form::label('NOMBRE', 'Nombre') !!}
  </div>
  <div class="col-md-4">
      {!! Form::text('NOMBRE', null, ['class' => 'form-control', 'placeholder' => 'Nombre de Proveedor', 'id' => 'nombre']); !!}
  </div>
</div>

<div class="row form-group">
  <div class="col-md-2 col-md-offset-1">
        {!! Form::label('DOMICILIO', 'Dirección') !!}
  </div>
  <div class="col-md-4">
      {!! Form::text('DOMICILIO', null, ['class' => 'form-control', 'placeholder' => 'Dirección de Proveedor', 'id' => 'domicilio']); !!}
  </div>
</div>

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

         $('#tipoproveedor_id').select2({
            placeholder: 'Seleccione un Proveedor'
        })
    });
</script>
@endpush
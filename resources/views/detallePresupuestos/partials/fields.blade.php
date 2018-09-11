@if (isset($detallePresupuesto->PRESUPUESTO_ID))
    <input id="PRESUPUESTO_ID" name="PRESUPUESTO_ID" type="hidden" value="{{ $detallePresupuesto->PRESUPUESTO_ID }}">
@endif
@if (isset($presupuesto_id))
    <input id="PRESUPUESTO_ID" name="PRESUPUESTO_ID" type="hidden" value="{{ $presupuesto_id }}">
    @endif
    
<input id="EMPRESA_ID" name="EMPRESA_ID" type="hidden" value="{{ Session::get('empresa') }}">


{!! Form::hidden('TIPO_GASTO', $tipo) !!}
{!! Form::hidden('ES_DEPRECIACION', $esDepreciacion) !!}

<div class="panel panel-primary">
  <div class="panel-heading">Detalle Presupuesto </div>
  <div class="panel-body">
    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('TIPOGASTO_ID', 'Categoria Gasto') !!}
      </div>
      <div class="col-md-3">
          {!! Form::select('TIPOGASTO_ID', $tipoGasto, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Opción', 'id' => 'tipo']); !!}
      </div>

    </div>

      {{--
      <div class="row form-group">
          <div class="col-md-2 col-md-offset-1">
              {!! Form::label('TIPOGASTO_ID', 'Subcategoria Gasto') !!}
          </div>
          <div class="col-md-3">
              {!! Form::select('TIPOGASTO_ID', $subTipoGasto, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Opción', 'id' => 'tipo']); !!}
          </div>
      </div>
      --}}
    @if($esDepreciacion == 0)
      <div class="row form-group asignacion" style="display: none">
          <div class="col-md-2 col-md-offset-1">
              {!! Form::label('TIPOASIGNACION_ID', 'Tipo Asignación') !!}
          </div>
          <div class="col-md-2">              
                {!! Form::radio('TIPOASIGNACION_ID', 1, true, ['id' => 'dinero']); !!}  DINERO
          </div>
          <div class="col-md-2">
              {!! Form::radio('TIPOASIGNACION_ID', 2, false, ['id' => 'unidad']); !!}  UNIDAD
          </div>
      </div>     
     
        <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
                {!! Form::label('MONTO', 'Asignación', ['id' => 'monto']) !!}
        </div>
        <div class="col-md-3">
            {!! Form::text('MONTO', null, ['class' => 'form-control', 'placeholder' => 'Ingrese Asignación']); !!}
        </div>
            <div class="col-md-3" style="display: none" id="unidades">
                <div class="form-control" id="tipoAsignacion"></div>
            </div>
        </div>
    @endif

    <div class="row form-group">
      <div class="col-md-1 col-md-offset-1">
            {!! Form::label('FRECUENCIATIEMPO_ID', 'Frecuencia') !!}
      </div>
      <div class="col-md-3 col-md-offset-1" >
              {!! Form::select('FRECUENCIATIEMPO_ID', $frecuencia, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Frecuencia']); !!}
      </div>
      {!! Form::hidden('DESCPROYECTO', null, ['id' => 'DESCPROYECTO'] ); !!}
      <div class="col-md-1">
        {!! Form::label('PROYECTO', 'Proyecto') !!}
      </div>
      <div class="col-md-3" id="proj">
          {!! Form::select('PROYECTO', [$detallePresupuesto->PROYECTO => $detallePresupuesto->DESCPROYECTO], null, ['class' => 'form-control']); !!}
      </div>
                
      <div class="col-md-1">
          <span class="glyphicon glyphicon-import" aria-hidden="true" id="project" style="cursor: pointer"></span>
      </div>
    </div>
    <hr>
    
    {!! Form::hidden('DESCCENTRO1', null, ['id' => 'DESCCENTRO1'] ); !!}    
    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('CENTROCOSTO1', 'Centro de Costo1') !!}
      </div>
      <div class="col-md-3" id="cc_1">
          {!! Form::select('CENTROCOSTO1', [$detallePresupuesto->CENTROCOSTO1 => $detallePresupuesto->DESCCENTRO1], null, ['class' => 'form-control']); !!}
      </div>
            
      <div class="col-md-1">
        <span class="glyphicon glyphicon-import" aria-hidden="true" id="centro_1" style="cursor: pointer"></span>
      </div>
    </div>

    {!! Form::hidden('DESCCENTRO2', null, ['id' => 'DESCCENTRO2'] ); !!}
    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('CENTROCOSTO2', 'Centro de Costo2') !!}
      </div>
      <div class="col-md-3" id="cc_2">
          {!! Form::select('CENTROCOSTO2', [$detallePresupuesto->CENTROCOSTO2 => $detallePresupuesto->DESCCENTRO2], null, ['class' => 'form-control']); !!}
      </div>
      
      <div class="col-md-1">
        <span class="glyphicon glyphicon-import" aria-hidden="true" id="centro_2" style="cursor: pointer"></span>
      </div>
    </div>

    {!! Form::hidden('DESCCENTRO3', null, ['id' => 'DESCCENTRO3'] ); !!}
    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('CENTROCOSTO3', 'Centro de Costo3') !!}
      </div>
      <div class="col-md-3" id="cc_3">
          {!! Form::select('CENTROCOSTO3', [$detallePresupuesto->CENTROCOSTO3 => $detallePresupuesto->DESCCENTRO3], null, ['class' => 'form-control']); !!}
      </div>      
      <div class="col-md-1">
        <span class="glyphicon glyphicon-import" aria-hidden="true" id="centro_3" style="cursor: pointer"></span>
      </div>
    </div>
 
    {!! Form::hidden('DESCCENTRO4', null, ['id' => 'DESCCENTRO4'] ); !!}
    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('CENTROCOSTO4', 'Centro de Costo4') !!}
      </div>
      <div class="col-md-3" id="cc_4">
          {!! Form::select('CENTROCOSTO4', [$detallePresupuesto->CENTROCOSTO4 => $detallePresupuesto->DESCCENTRO4], null, ['class' => 'form-control']); !!}
      </div>
      
      <div class="col-md-1">
        <span class="glyphicon glyphicon-import" aria-hidden="true" id="centro_4" style="cursor: pointer"></span>
      </div>
    </div>

    {!! Form::hidden('DESCCENTRO5', null, ['id' => 'DESCCENTRO5'] ); !!}
    <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('CENTROCOSTO5', 'Centro de Costo5') !!}
      </div>
      <div class="col-md-3" id="cc_5">
          {!! Form::select('CENTROCOSTO5', [$detallePresupuesto->CENTROCOSTO5 => $detallePresupuesto->DESCCENTRO5], null, ['class' => 'form-control']); !!}
      </div>
      
      <div class="col-md-1">
        <span class="glyphicon glyphicon-import" aria-hidden="true" id="centro_5" style="cursor: pointer"></span>
      </div>
    </div> 

   {{--  <div class="row form-group">
      <div class="col-md-2 col-md-offset-1">
            {!! Form::label('ANULADODP', 'Anular') !!}
      </div>
      <div class="col-md-4">
          {!! Form::checkbox('ANULADOPD', 1); !!}
      </div>
    </div> --}}

  </div>
</div>

<div class="loader-modal-container" id="loader">
    <img src="{{ asset('images/loader.gif') }}" alt="Loader Container" class="loader-info">  
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
        
        var tipo = $('#tipo').val()
        if(tipo != null) { 
                            
            var tipo_id = $('#tipo').val()
            vurl = '{{ route('detallePresupuestos.show') }}'
            vurl = vurl.replace('%7BdetallePresupuestos%7D', tipo_id);                
            $.ajax({
                type: 'get',
                url: vurl,                    
                success: function (data) {
                    if (data != 'NoAplica') {
                        $('#tipoAsignacion').html(data);                        
                        $('.asignacion').show()
                        var tipoAsignacion = $('#dinero').is(':checked')                        
                        if(tipoAsignacion == false) {                               
                            $('#unidades').show()
                        } else {
                            $('#unidades').hide()
                        }
                    } else {
                        $('.asignacion').hide()
                        $('#unidades').hide()
                    }                        
                }
            })                
            
        }

       $('#tipo').change(function () {                            
            var tipo_id = $('#tipo').val()
            vurl = '{{ route('detallePresupuestos.show')}}'
            vurl = vurl.replace('%7BdetallePresupuestos%7D', tipo_id);
            
            $.ajax({
                type: 'get',
                url: vurl,
                success: function (data) {
                    if (data != 'NoAplica') {
                        $('#tipoAsignacion').html(data);                        
                        $('.asignacion').show()            
                    } else {
                        $('.asignacion').hide()
                        $('#unidades').hide()
                    }                       

                }
            })            
        });

        $('#unidad').click(function() {
            $('#unidades').show()            
        })
        $('#dinero').click(function() {
            $('#unidades').hide()            
        })

        $('#project').click(function() {
            $('#loader').show()            
            var criterio = $('#EMPRESA_ID').val() + '-' + 8 
            vurl = '{{ route('codigoProyecto')}}'            
            vurl = vurl.replace('%7Bid%7D', criterio);            

            $.getJSON(vurl, null, function (values) {                
                $('#PROYECTO').populateSelect(values)
                $('#loader').hide() 
            })            
           
        })

        $('#PROYECTO').change(function() {            
            $('#DESCPROYECTO').val($('#PROYECTO option:selected').text())
        })

        $('#centro_1').click(function() {    
            $('#loader').show()        
            var criterio = $('#EMPRESA_ID').val() + '-' + 4 + '-' + 1            
            vurl = '{{ route('codigoCentroCostoUno')}}'            
            vurl = vurl.replace('%7Bid%7D', criterio);            

            $.getJSON(vurl, null, function (values) {                
                $('#CENTROCOSTO1').populateSelect(values)
                $('#loader').hide() 
            })            
           
        })
       
        $('#CENTROCOSTO1').change(function() {                
            console.log($('#CENTROCOSTO1 option:selected').text())
            $('#DESCCENTRO1').val($('#CENTROCOSTO1 option:selected').text())
        })

        $('#centro_2').click(function() {
            $('#loader').show() 
            var criterio = $('#EMPRESA_ID').val() + '-' + 4 + '-' + 2
            vurl = '{{ route('codigoCentroCostoDos')}}'
            vurl = vurl.replace('%7Bid%7D',criterio);

            $.getJSON(vurl, null, function (values) {                
                $('#CENTROCOSTO2').populateSelect(values)
                $('#loader').hide() 
            })   
        })

         $('#CENTROCOSTO2').change(function() {            
            $('#DESCCENTRO2').val($('#CENTROCOSTO2 option:selected').text())
        })

        $('#centro_3').click(function() {
            $('#loader').show() 
            var criterio = $('#EMPRESA_ID').val() + '-' + 4 + '-' + 3
            vurl = '{{ route('codigoCentroCostoTres')}}'
            vurl = vurl.replace('%7Bid%7D', criterio);

            $.getJSON(vurl, null, function (values) {                
                $('#CENTROCOSTO3').populateSelect(values)
                $('#loader').hide() 
            })   
        })

         $('#CENTROCOSTO3').change(function() {            
            $('#DESCCENTRO3').val($('#CENTROCOSTO3 option:selected').text())
        })

        $('#centro_4').click(function() {
            $('#loader').show() 
            var criterio = $('#EMPRESA_ID').val() + '-' + 4 + '-' + 4
            vurl = '{{ route('codigoCentroCostoCuatro')}}'
            vurl = vurl.replace('%7Bid%7D', criterio);

           $.getJSON(vurl, null, function (values) {                
                $('#CENTROCOSTO4').populateSelect(values)
                $('#loader').hide() 
            })   
        })

         $('#CENTROCOSTO4').change(function() {            
            $('#DESCCENTRO4').val($('#CENTROCOSTO4 option:selected').text())
        })

        $('#centro_5').click(function() {
            $('#loader').show() 
            var criterio = $('#EMPRESA_ID').val() + '-' + 4 + '-' + 5
            vurl = '{{ route('codigoCentroCostoCinco')}}'
            vurl = vurl.replace('%7Bid%7D', criterio);

            $.getJSON(vurl, null, function (values) {                
                $('#CENTROCOSTO5').populateSelect(values)
                $('#loader').hide() 
            })   
        })

         $('#CENTROCOSTO5').change(function() {            
            $('#DESCCENTRO5').val($('#CENTROCOSTO5 option:selected').text())
        })

    });
</script>
@endpush
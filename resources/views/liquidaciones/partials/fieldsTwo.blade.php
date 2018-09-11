@if (isset($usuario_id))
    <input id="USUARIO_ID" name="USUARIO_ID" type="hidden" value="{{ $usuario_id }}">
@endif

{!! Form::hidden('TIPO_LIQUIDACION', $tipoLiquidacion) !!}
<hr>
<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('USUARIO', 'Usuario') !!}
  </div>
  <div class="col-md-3"> 
      {!! Form::text('USUARIO', $usuario->nombre, ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>

  <div class="col-md-1">
      {!! Form::label('RUTA_ID', ($tipoLiquidacion == 1) ? 'Rutas' : 'Gastos' ) !!}
  </div>
  <div class="col-md-3">
      {!! Form::select('RUTA_ID', $rutas, $combo->RUTA, ['class' => 'form-control', 'placeholder' => 'Seleccione una Ruta']); !!}
  </div>


  
</div>

<div class="row form-group">
    <div class="col-md-1 col-md-offset-1">
        {!! Form::label('FECHA_INICIO', 'Fecha Inicio') !!}
    </div>
    <div class="col-md-2">
        @if($liquidacion->FECHA_INICIO)
            {!! Form::date('FECHA_INICIO', $liquidacion->FECHA_INICIO, ['class' => 'form-control']); !!}
        @else
            {!! Form::date('FECHA_INICIO', null, ['class' => 'form-control']); !!}
        @endif
    </div>
    <div class="col-md-1">
        {!! Form::label('FECHA_FINAL', 'Fecha Final') !!}
    </div>
    <div class="col-md-2">
        @if($liquidacion->FECHA_FINAL)
            {!! Form::date('FECHA_FINAL', $liquidacion->FECHA_FINAL, ['class' => 'form-control']); !!}
        @else
            {!! Form::date('FECHA_FINAL', null, ['class' => 'form-control']); !!}
        @endif
    </div>

    <div class="col-md-1">
        {!! Form::label('TOTAL', 'Detalle Presupuesto') !!}
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-list-alt" aria-hidden="true" style="font-size:32px; color: black"></button>
    </div>
</div>

<div class="row form-group">
    <div class="col-md-1 col-md-offset-1">
        {!! Form::label('TOTAL', 'Total Facturado') !!}
    </div>
    <div class="col-md-2">
        {!! Form::text('TOTAL', number_format($total, 2), ['class' => 'form-control text-right', 'disabled' => 'true', 'id' => 'totalLiquidacion']); !!}
    </div>
    
    <div class="col-md-1">
        {!! Form::label('REMANENTE', 'No Aplica Pago') !!}
    </div>
    <div class="col-md-2">
        {!! Form::text('REMANENTE', number_format($noAplicaPago, 2), ['class' => 'form-control text-right', 'disabled' => 'true']); !!}
    </div> 

    <div class="col-md-1">
        {!! Form::label('REEMBOLSO', 'Reembolso') !!}
    </div>
    <div class="col-md-2">
        {!! Form::text('REEMBOLSO', number_format($total - $noAplicaPago, 2), ['class' => 'form-control text-right', 'disabled' => 'true']); !!}
    </div> 
</div>

<div class="row form-group">
    <div class="col-md-1 col-md-offset-1">
        {!! Form::label('COMENTARIO_PAGO', 'Comentario') !!}
    </div>
    <div class="col-md-8">
        {!! Form::textarea('COMENTARIO_PAGO', null, ['class' => 'form-control', 'rows' => '2', 'cols' => '500', 'placeholder' => 'Observaciones y/o Comentarios']); !!}
    </div>
  {{--  <div class="col-md-1">
      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModalTwo"><span class="glyphicon glyphicon-floppy-remove" aria-hidden="true" style="font-size:32px; color: black" data-toggle="tooltip" data-placement="top" title="Anular"></span></button>
  </div>  --}}
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

@if($liquidacion->SUPERVISOR_COMENTARIO)
    <div class="row form-group">
        <div class="col-md-1 col-md-offset-1">
            {!! Form::label('COMENTARIO_SUPERVISOR', 'Rechazo Supervisor') !!}
        </div>
        <div class="col-md-8">
            {!! Form::textarea('COMENTARIO_SUPERVISOR', $liquidacion->SUPERVISOR_COMENTARIO, ['class' => 'form-control', 'rows' => '2', 'cols' => '500', 'placeholder' => 'Observaciones y/o Comentarios', 'disabled' => 'true']); !!}
        </div>
    </div>
@endif

@if($liquidacion->CONTABILIDAD_COMENTARIO)
    <div class="row form-group">
        <div class="col-md-1 col-md-offset-1">
            {!! Form::label('COMENTARIO_CONTABILIDAD', 'Rechazo Contabilidad') !!}
        </div>
        <div class="col-md-8">
            {!! Form::textarea('COMENTARIO_CONTABILIDAD', $liquidacion->CONTABILIDAD_COMENTARIO, ['class' => 'form-control', 'rows' => '2', 'cols' => '500', 'placeholder' => 'Observaciones y/o Comentarios', 'disabled' => 'true']); !!}
        </div>
    </div>
@endif

<div class="row form-group">
        <div class="col-md-1  col-md-offset-1 col-xs-1 col-xs-offset-3">
            <button type="button" class="btn btn-default"><a href="{{ route('exportarExcel', $liquidacion->ID) }}"><span class="glyphicon glyphicon-download-alt" aria-hidden="true" style="font-size:32px; color: black" data-toggle="tooltip" data-placement="top" title="Descargar"></span></a></button>
        </div>
        
        <div class="col-md-1 col-md-offset-7 col-xs-1 col-xs-offset-1">
    
            <a href="{{ route('enviarLiquidacion', $liquidacion->ID . '-' . $tipoLiquidacion) }}">
                <button type="button" class="btn btn-default" ><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true" style="font-size:32px; color: black" data-toggle="tooltip" data-placement="top" title="Enviar"></button>
            </a>
        </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Detalle Presupuesto</h4>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                  <th class="text-center">Categoría Gasto</th>
                  <th class="text-center">Monto</th>
                  <th class="text-center">Tipo</th>                  
                  <th class="text-center">Frecuencia</th>                  
                </thead>
                <tbody>                    
                    @if(isset($presupuestoDepreciacion))                    
                        <td>{{ $presupuestoDepreciacion['TIPOGASTO'] }}</td>
                        <td class="text-right">{{ $presupuestoDepreciacion['MONTO'] }}</td>
                        <td>{{ $presupuestoDepreciacion['DESCRIPCION'] }}</td>
                        <td>{{ $presupuestoDepreciacion['FRECUENCIA'] }}</td>
                    @else                    
                        @foreach ($presupuestoAsignado as $presupuesto)
                            <tr>
                                <td>{{ $presupuesto->TIPOGASTO }}</td>
                                <td class="text-right">{{ $presupuesto->MONTO }}</td>
                                <td>{{ $presupuesto->DESCRIPCION }} </td>                            
                                <td>{{ $presupuesto->FRECUENCIA }}</td>                            
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
          {{-- <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>
          <button type="button" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" id="ok_exenta"></button> --}}
        </div>
      </div>
    </div>
  </div>
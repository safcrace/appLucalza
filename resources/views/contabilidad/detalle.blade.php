<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('LIQUIDACION', 'No. ') !!}
  </div>
  <div class="col-md-2">
        {!! Form::text('LIQUIDACION', $liquidacion->ID, ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>
  <div class="col-md-1">
        {!! Form::label('RUTA_ID', 'Ruta') !!}
  </div>
  <div class="col-md-2">
        {!! Form::text('RUTA_ID', $liquidacion->RUTA, ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>

  <div class="col-md-1">
    {!! Form::label('USUARIO', 'Usuario') !!}
  </div>
  <div class="col-md-3">
      {!! Form::text('USUARIO', $liquidacion->USUARIO, ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>  
</div>

<div class="row form-group">
  
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('FECHA', 'Fecha Inicio') !!}
  </div>
  <div class="col-md-2">
        {!! Form::text('FECHA', $liquidacion->FECHA_INICIO->format('d-m-Y'), ['class' => 'form-control', 'disabled' => 'true']); !!}
  </div>
  <div class="col-md-1">
    {!! Form::label('FECHA', 'Fecha Final') !!}
</div>
<div class="col-md-2">
    {!! Form::text('FECHA', $liquidacion->FECHA_FINAL->format('d-m-Y'), ['class' => 'form-control', 'disabled' => 'true']); !!}
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
  <div class="col-md-1 col-md-offset-3 col-xs-1 col-xs-offset-2">
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModalTwo"><span class="glyphicon glyphicon-floppy-remove" aria-hidden="true" style="font-size:32px; color: black" data-toggle="tooltip" data-placement="top" title="Corregir"></span></button>
  </div>
  <div class="col-md-1 col-xs-1 col-xs-offset-1">
  {{--  {!! Form::model($liquidacion, ['route' => ['aprobacionLiquidacion', $liquidacion->ID], 'method' => 'PATCH']) !!}  --}}
    <a href="{{ route('reporteContabilidad', $liquidacion->ID) }}">
      <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true" style="font-size:32px; color: black" data-toggle="tooltip" data-placement="top" title="Enviar a SAP"></button>
    </a>
  {{--  {!! Form::close() !!}  --}}
  </div>
  <div class="col-md-1 col-xs-1 col-xs-offset-1">
    <a href="{{ route('reporteContabilidad', $liquidacion->ID) }}">
      <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-download-alt" aria-hidden="true" style="font-size:32px; color: black" data-toggle="tooltip" data-placement="top" title="Descargar"></button>
    </a>
  </div>
</div>
  

<div class="row form-group">
  @if($liquidacion->SUPERVISOR_COMENTARIO)
    <div class="col-md-1">
      {!! Form::label('COMENTARIO_SUPERVISOR', 'Rechazo Supervisor') !!}
    </div>
    <div class="col-md-5">
      {!! Form::textarea('COMENTARIO_SUPERVISOR', $liquidacion->SUPERVISOR_COMENTARIO, ['class' => 'form-control', 'rows' => '2', 'cols' => '500', 'placeholder' => 'Observaciones y/o Comentarios', 'disabled' => 'true']); !!}
    </div>
  @endif
  @if($liquidacion->CONTABILIDAD_COMENTARIO)
    <div class="col-md-1">
      {!! Form::label('COMENTARIO_CONTABILIDAD', 'Rechazo Contabilidad') !!}
    </div>
    <div class="col-md-5">
      {!! Form::textarea('COMENTARIO_CONTABILIDAD', $liquidacion->CONTABILIDAD_COMENTARIO, ['class' => 'form-control', 'rows' => '2', 'cols' => '500', 'placeholder' => 'Observaciones y/o Comentarios', 'disabled' => 'true']); !!}
    </div>
  @endif
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
                            <td>{{ $presupuesto->DESCRIPCION }}</td>
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


@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
            {!! Form::model($factura, ['route' => ['facturas.update', $factura->ID], 'method' => 'PATCH']) !!}
            <div class="panel panel-primary">
                 <div class="panel-heading panel-title" style="height: 65px">
                    Editar Factura {{ $factura->NUMERO }}
                     <button type="button" class="btn btn-default" style="border-color: white; float: right"><a href="{{ route('liquidaciones.edit', $factura->LIQUIDACION_ID . '-' . $tipoLiquidacion) }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                     <button type="submit" class="btn btn-default" style="border-color: white; float: right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;" data-toggle="tooltip" data-placement="top" title="Grabar"></button>
                  </div>
                 <div class="panel-body">

                     @include('facturas.partials.fields')

                  {!! Form::close() !!}
                 </div>
               </div>

               

              </div>

              {{--  Ventana para sustituir imagen de factura  --}}

              <div class="modal fade" id="myModalS" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="myModalLabel">Cargar Nueva Imagen de Factura</h4>
                          </div>

                          <div class="modal-body">
                              {!! Form::open(['route' => 'sustituirFactura', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'form-update']) !!}
                                  {!! Form::file('FOTO'); !!}
                                  {!! Form::hidden('URL_IMAGENFACTURA', null, ['class' => 'form-control', 'id' => 'facturaUrl']); !!}
                                  {!! Form::hidden('ID_FACTURA', null, ['class' => 'form-control', 'id' => 'id_factura']); !!}
                              
                            
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>
                              <button type="submit" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;"></button>
                          </div>
                          {!! Form::close() !!}
                      </div>
                  </div>
              </div>
        </div>


  </div>
@endsection

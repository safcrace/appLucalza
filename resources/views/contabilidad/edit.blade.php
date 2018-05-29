@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
            <div class="panel panel-primary">
                 <div class="panel-heading panel-title" style="height: 65px">
                    Datos Liquidación

                      <button type="button" class="btn btn-default text-right" style="border-color: white; float: right"><a href="{{ route('contabilidad') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>

                  </div>
                 <div class="panel-body">


                     @include('contabilidad.detalle')
                  {!! Form::close() !!}
                 </div>
               </div>

               <div class="panel panel-primary">
                   <div class="panel-heading panel-title">Detalle de Facturación</div>





                   <div class="panel-body">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped table-hover">
                        <thead>
                          <th class="text-center">No.</th>
                          <th class="text-center">Fecha Factura</th>
                          <th class="text-center">Proveedor</th>
                          <th class="text-center">Serie</th>
                          <th class="text-center">Numero</th>
                          <th class="text-center">Tipo de Gasto</th>
                          <th class="text-center">Total</th>
                          <th class="text-center">Ver</th>
                          <th class="text-center">Corregir</th>
                          <th class="text-center">Comentario Supervisor</th>
                          <th class="text-center">Comentario Contabilidad</th>
                          {{--<th class="text-center">Corregir</th>--}}
                        </thead>
                        <tbody>

                            @foreach ($facturas as $factura)
                                <tr data-id={{ $factura->ID }} data-factura={{ $factura->NUMERO }} data-proveedor={{ $factura->PROVEEDORID }}>
                                    <td>{{ $factura->CORRELATIVO}}</td>
                                    <td>{{ $factura->FECHA_FACTURA->format('d-m-Y') }}</td>
                                    @if($factura->TIPOPROVEEDOR_ID == 1)
                                        <td style="background-color: red; color: white;" class="proveedor">{{ $factura->NOMBRE}} </td>
                                    @else
                                        <td>{{ $factura->NOMBRE}}</td>
                                    @endif
                                    <td>{{ $factura->SERIE}}</td>
                                    <td>{{ $factura->NUMERO}}</td>
                                    <td>{{ $factura->TIPOGASTO}}</td>
                                    <td>Q.{{ $factura->TOTAL}}</td>
                                    <td class="text-center">
                                        <a class="image-popup-fit-width" href='{{ asset("images/$factura->EMAIL/$factura->FOTO") }}' title="Imagen Factura.">
                                            <img src='{{ asset("images/$factura->EMAIL/$factura->FOTO") }}' height="32px">
                                        </a>
                                    </td>
                                    @if($factura->CORRECCION == 1)
                                    hola
                                        <td class="text-center" style="background-color: red;"><span class="glyphicon glyphicon-pencil btn_corregir" aria-hidden="true" style="font-size:20px; color: black" data-toggle="modal" data-target="#myModal"></td>
                                    @else
                                        <td class="text-center"><span class="glyphicon glyphicon-pencil btn_corregir" aria-hidden="true" style="font-size:20px; color: black" data-toggle="modal" data-target="#myModal"></td>
                                    @endif
                                    <td class="text-center" style="max-widht: 200px" >
                                        <button type="button" class="btn btn-sm btn-default" data-toggle="popover" data-trigger="focus" title="Comentario Supervisor" data-content="{{ $factura->COMENTARIO_SUPERVISOR }}">
                                            {{ substr($factura->COMENTARIO_SUPERVISOR, 1, 10) }}
                                        </button>
                                    </td>
                                    <td style="max-widht: 200px" class="text-center">
                                        <button type="button" class="btn btn-sm btn-default" data-toggle="popover" data-trigger="focus" title="Comentario Contabilidad" data-content="{{ $factura->COMENTARIO_CONTABILIDAD }}">
                                            {{ substr($factura->COMENTARIO_CONTABILIDAD, 1, 10) }}
                                        </button>
                                    </td>
                                    {{--<td class="text-center">{!! Form::checkbox('Corregir', true, false, ['class' => 'btn_corregir']); !!}</td>--}}
                                </tr>
                            @endforeach


                        </tbody>


                       </table>
                    </div>   
                       <div class="text-center">
                         {{--!!$factura->render()!! | , 'data-toggle' => 'modal', 'data-target' => '#myModal' --}}
                       </div>
                   </div>
                   </div>


              </div>
        </div>
  </div>

  <div class="" id="facturaId" style:"display: none"></div>
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      {!! Form::model($factura, ['route' => ['comentarioContabilidad', ':FACTURA_ID'], 'method' => 'PATCH', 'id' => 'form-update']) !!}
      <div class="modal-body">

          <textarea name="COMENTARIO_CONTABILIDAD" id="COMENTARIO_CONTABILIDADF" class="form-control" rows="3"></textarea>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>
        <button type="submit" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign btn_enviar" aria-hidden="true" style="font-size:32px; color: black;"></button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
  </div>

  {{-- Corrección Liquidación --}}

  <div class="modal fade" id="myModalTwo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabelTwo">Comentario Liquidación No. {{ $liquidacion->ID }}</h4>
      </div>
      {!! Form::model($liquidacion, ['route' => ['correccionLiquidacionContabilidad', $liquidacion->ID], 'method' => 'PATCH']) !!}
      <div class="modal-body">

          <textarea name="CONTABILIDAD_COMENTARIO" class="form-control" rows="3"></textarea>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>
        <button type="submit" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;"></button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
  </div>

  {{-- Corrección Liquidación --}}

  <div class="modal fade" id="myModalThree" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabelThree">Actualización de Proveedor</h4>
              </div>
              {{--!! Form::model($liquidacion, ['route' => ['correccionLiquidacionContabilidad', $liquidacion->ID], 'method' => 'PATCH']) !!--}}
              <div class="modal-body">

                  @include('contabilidad.proveedor')

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>
                  <button type="button" class="btn btn-default" style="border-color: white" id="actualizarProveedor"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;"></button>
              </div>
              {{--!! Form::close() !! --}}
          </div>
      </div>
  </div>
@endsection

@push('scripts')
  <script type="text/javascript">
      //var corregirFactura = [];
      $(".btn_corregir").click(function(e){
        var row = $(this).parents('tr');
        {{--row.find('.btn_aprobar').prop( "checked", false );--}}
        var factura = row.data('factura');
        var id = row.data('id');
        $("#facturaId").html(id);
        $("#myModalLabel").html('Factura No. ' + factura);
      });

      $(".btn_enviar").click(function(e){
          e.preventDefault();
          var comentarioContabilidad = $('#COMENTARIO_CONTABILIDADF').val()  
          if (!comentarioContabilidad) {
            alert('Debe registrar un comentario!')
          } else {              
            var form = $('#form-update');
            var id = $("#facturaId").html();
            var url = form.attr('action').replace(':FACTURA_ID', id);
            var data = form.serialize();

            $.post(url, data, function (result){            
                $('#myModal').modal('hide');
                location.reload();
            })
          }
      });

      $('.popup-gallery').magnificPopup({
          delegate: 'a',
          type: 'image',
          tLoading: 'Loading image #%curr%...',
          mainClass: 'mfp-img-mobile',
          gallery: {
              enabled: true,
              navigateByImgClick: true,
              preload: [0,1] // Will preload 0 - before current, and 1 after the current image
          },
          image: {
              tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
              titleSrc: function(item) {
                  return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
              }
          }
      });
      $('.image-popup-vertical-fit').magnificPopup({
          type: 'image',
          closeOnContentClick: true,
          mainClass: 'mfp-img-mobile',
          image: {
              verticalFit: true
          }
      });
      $('.image-popup-fit-width').magnificPopup({
          type: 'image',
          closeOnContentClick: true,
          image: {
              verticalFit: false
          }
      });
      $('.image-popup-no-margins').magnificPopup({
          type: 'image',
          closeOnContentClick: true,
          closeBtnInside: false,
          fixedContentPos: true,
          mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
          image: {
              verticalFit: true
          },
          zoom: {
              enabled: true,
              duration: 300 // don't foget to change the duration also in CSS
          }
      });

      $('.proveedor').click(function() {
          var row = $(this).parents('tr');
          var proveedor_id = row.data('proveedor');
          $('#proveedor_id').val(proveedor_id)
          //alert(proveedor_id)
          $('#myModalThree').modal('show')
      })

      $('#actualizarProveedor').click(function () {

          var tipoProveedor = $('#tipoproveedor_id').val()
          var proveedor_id = $('#proveedor_id').val()
          var numero_liquidacion = $('#numero_liquidacion').val()


          vurl = '{{ route('actualizarProveedor')}}'
          vurl = vurl.replace('%7Bid%7D', tipoProveedor + '-' + proveedor_id + '-' + numero_liquidacion);


          $.ajax({
              type: 'get',
              url: vurl,
              success: function (data) {
                  location.reload(); //$('#vendedores').empty().html(data);
              }
          })
      });

      $(function () {
          $('[data-toggle="popover"]').popover()
      })
  </script>
@endpush

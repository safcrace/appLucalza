@extends('layouts.app')

@include('layouts.headerTwo')
@section('content')

  <div class="container">
      <div class="row">
          <div class="col-md-12 ">

            <div class="text-center" style="margin-bottom: 50px; color: #1D0068;">
                <h2>REPORTE LIQUIDACION No.{{ $liquidacion->ID }} </h1>        
                <h3>{{ $liquidacion->USUARIO }} - Ruta: {{ $liquidacion->RUTA }}</h3>
                <h3>Del {{ $liquidacion->FECHA_INICIO->format('d-m-Y') }} Al {{ $liquidacion->FECHA_FINAL->format('d-m-Y') }}</h3>
            </div>
            

            <table class="table table-bordered table-striped table-hover">
            <thead>
                <th class="text-center">Fecha Documento</th>
                <th class="text-center">Documento</th>
                <th class="text-center">Serie</th>
                <th class="text-center">Número</th>
                <th class="text-center">Tipo de Gasto</th>
                <th class="text-center">Proveedor</th>
                <th class="text-center">Total</th>
                <th class="text-center">Monto Afecto</th>
                <th class="text-center">Monto Exento</th>
                <th class="text-center">Monto Iva</th>
                <th class="text-center">Monto Remanente</th>
                <th class="text-center">Autorizado</th>
                {{--<th class="text-center">Corregir</th>--}}
            </thead>
            <tbody>

                @foreach ($facturas as $factura)
                    <tr data-id={{ $factura->ID }} data-factura={{ $factura->NUMERO }} data-proveedor={{ $factura->PROVEEDORID }}>
                        <td>{{ $factura->FECHA_FACTURA->format('d-m-Y') }}</td>                        
                        <td>{{ $factura->DOCUMENTO}}</td>                        
                        <td>{{ $factura->SERIE}}</td>
                        <td>{{ $factura->NUMERO}}</td>
                        <td>{{ $factura->TIPOGASTO}}</td>
                        <td>{{ $factura->NOMBRE}}</td>
                        <td>Q.{{ $factura->TOTAL}}</td>
                        <td>Q.{{ $factura->MONTO_AFECTO}}</td>
                        <td>Q.{{ $factura->MONTO_EXENTO}}</td>
                        <td>Q.{{ $factura->MONTO_IVA}}</td>
                        <td>Q.{{ $factura->MONTO_REMANENTE}}</td>
                        <td>{{ ($factura->APROBACION_PAGO)  ? 'SI' : 'NO' }}</td>                                        
                    </tr>
                @endforeach
            </tbody>
            </table>         

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

          var form = $('#form-update');
          var id = $("#facturaId").html();
          var url = form.attr('action').replace(':FACTURA_ID', id);
          var data = form.serialize();

          $.post(url, data, function (result){            
            $('#myModal').modal('hide');
            location.reload();
          })
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

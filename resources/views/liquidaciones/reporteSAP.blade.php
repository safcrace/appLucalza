@extends('layouts.app')

  @include('layouts.headerTwo')
  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-12 ">
              <div class="panel panel-primary">
                  <div class="panel-heading panel-title" style="height: 65px">Reporte de Inserción a SAP ::  Liquidación No. {{$id}}
                      <button type="button" class="btn btn-default hidden-xs" style="border-color: white; float: right"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      <button type="button" class="btn btn-default btn-xs visible-xs" style="border-color: white; float: right"><a href="{{ route('home') }}" title="Cerrar"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:24px; color: black"></span></a></button>
                      <button type="button" class="btn btn-default hidden-xs" style="border-color: white; float: right"><a href="{{ route('liquidacionCreate', 1 ) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
                      <button type="button" class="btn btn-default btn-xs visible-xs" style="border-color: white; float: right"><a href="{{ route('liquidacionCreate', 1 ) }}" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:24px; color: black"></span></a></button>
                  </div>

                  <div class="panel-body">
                   
                  
                  
                  <h3 class="text-center">Documentos Insertados: {{ $insertados }}</h3>
                  <h3 class="text-center">Documentos No Insertados: {{ $noInsertados }}</h3>

                    <hr>
                  <h2 class="text-center">Detalle de Facturas no Insertadas</h2>
                  <div class="table-responsive"> 
                        <table class="table table-bordered table-striped table-hover">
                          <thead>
                            <th class="text-center">Factura</th>
                            <th class="text-center">Problema</th>                            
                          </thead>
                          <tbody>
                            @foreach ($respuesta as $item)
                                @if ($item['result'] != 0)
                                    <tr>
                                        <td>{{ $item['numAtCard'] }}</td>
                                        <td>{{ $item['message'] }}</td>                                    
                                    </tr>
                                @endif  
                            @endforeach
                          </tbody>
                        </table>
                  <div class="text-center">
                      <button class="btn-primary ali"><a href="{{ route('home')}}" style="color:#ffffff">OK</a></button>      
                  </div>      
                  </div>



                                     

                           {{-- @foreach ($liquidaciones as $liquidacion)
                               <tr data-id="{{  $liquidacion->ID }}">
                                   <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID . '-' . $tipoLiquidacion) }}">{{ $liquidacion->ID }}</td>
                                   <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID . '-' . $tipoLiquidacion) }}">{{ $liquidacion->FECHA_INICIO->format('d-m-Y') }}</td>
                                   <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID . '-' . $tipoLiquidacion) }}">{{ $liquidacion->FECHA_FINAL->format('d-m-Y') }}</td>
                                   <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID . '-' . $tipoLiquidacion) }}">{{ $liquidacion->RUTA }}</td>
                                   <td class="text-right"><a href="{{ route('liquidaciones.edit', $liquidacion->ID . '-' . $tipoLiquidacion) }}">{{ App\Factura::where('LIQUIDACION_ID', '=', $liquidacion->ID)->where('ANULADO', '=', 0)->sum('TOTAL')}}</td>
                                   @if($liquidacion->DESCRIPCION == 'En Corrección')
                                        <td style="background-color: red; color: #ffffff;"><a href="{{ route('liquidaciones.edit', $liquidacion->ID . '-' . $tipoLiquidacion) }}" style="text-decoration:none; color: white;">{{ $liquidacion->DESCRIPCION }}</td>
                                   @else
                                        <td><a href="{{ route('liquidaciones.edit', $liquidacion->ID . '-' . $tipoLiquidacion) }}">{{ $liquidacion->DESCRIPCION }}</td>
                                   @endif
                                   <td class="text-center"><a href="{{ route('liquidaciones.edit', $liquidacion->ID ) }}">{{ ($liquidacion->ANULADO)?'SI':'' }}</a></td>
                                   <td class="text-center">
                                     <a href="{{ route('anularLiquidacion', $liquidacion->ID) }}" class="btn-delete"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                                   </td>
                               </tr>
                            @endforeach --}}

                       
                   
                      
                  </div>
            </div>

        </div>
    </div>

     

  </div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        console.log('listo para lo que se ofrezca!')
    });
</script>
@endpush
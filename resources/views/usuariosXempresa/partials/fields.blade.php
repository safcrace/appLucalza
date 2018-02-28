{{--{!! Form::open(['route' => ['correccionLiquidacion'], 'method' => 'POST']) !!} --}}

<div class="row form-group">
  <div class="col-md-1 col-md-offset-1">
        {!! Form::label('USUARIO_ID', 'Usuario') !!}
  </div>
  <div class="col-md-3">
      @if(isset($usuarioAsignado))
        {!! Form::select('USUARIO_ID', $usuarios, $usuarioAsignado, ['class' => 'form-control', 'placeholder' => 'Seleccione un Usuario', 'id' => 'usuario']); !!}
      @else
        {!! Form::select('USUARIO_ID', $usuarios, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un Usuario', 'id' => 'usuario']); !!}
      @endif
  </div>  
</div>
<div id="agregarEmpresa" style="display: none">
    <hr>
    <h3 class="text-center">Asignación de Empresa</h3>
    <hr>

    <div class="row form-group">
        <div class="col-md-1 col-md-offset-1">
            <label for="EMPRESA_ID">Empresa</label>
        </div>
        <div class="col-md-4">
            {!! Form::select('EMPRESA_ID', $empresas, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Empresa', 'id' => 'empresa']); !!}
        </div>
    </div>

    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('codigoProveedorSap', 'Código Proveedor SAP') !!}
        </div>
        <div class="col-md-3" id="cod_pro">
            {!! Form::text('ProveedorSap', null, ['class' => 'form-control', 'placeholder' => 'Código Proveedor SAP', 'id' => 'codigoProveedorSap', 'disabled']); !!}

        </div>
        <div class="col-md-3" id="pro_sap" style="display: none">

        </div>

        {{--  <div class="col-md-1 col-md-offset-1">
            <span class="glyphicon glyphicon-import" aria-hidden="true" id="proveedorSap" style="cursor: pointer"></span>
        </div>                --}}
    </div>

    <div class="row form-group">
        <div class="col-md-2 col-md-offset-1">
            {!! Form::label('ID_USERSAP', 'Código Usuario SAP') !!}
        </div>
        <div class="col-md-3" id="textoCodigo">
            @if(isset($id_usersap))
            {!! Form::text('id_usersap', $id_usersap, ['class' => 'form-control', 'placeholder' => 'Código Usuario', 'disabled']); !!}
            @else
            {!! Form::text('id_usersap', null, ['class' => 'form-control', 'placeholder' => 'Código Usuario', 'disabled']); !!}
            @endif
        </div>
        <div class="col-md-3" id="selectCodigo" style="display: none"></div>
        {{--  <div class="col-md-1 col-md-offset-1">
            <span class="glyphicon glyphicon-import" aria-hidden="true" id="codigoSap" style="cursor: pointer"></span>
        </div>  --}}
    </div>
    {!! Form::hidden('DESCRIPCION_PROVEEDORSAP', null, ['class' => 'form-control', 'id' => 'descripcion_proveedorsap']); !!}

</div>
<div class="col-md-12" id="agregar" style="display: none">
        <button type="button" class="btn btn-default" style="border-color: white; float: right" id="botonAgregar"><a href="#" title="Agregar Empresa"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></a></span></button>
</div>
{{--  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Asignación de Empresa</h4>
      </div>

      <div class="modal-body">

          <div class="row form-group">
              <div class="col-md-3 ">
                  <label for="EMPRESA_ID">Empresa</label>
              </div>
              <div class="col-md-4">
                  {!! Form::select('EMPRESA_ID', $empresas, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Empresa', 'id' => 'empresa']); !!}
              </div>
          </div>

          <div class="row form-group">
              <div class="col-md-3">
                  {!! Form::label('codigoProveedorSap', 'Código Proveedor SAP') !!}
              </div>
              <div class="col-md-3" id="cod_pro">
                  {!! Form::text('ProveedorSap', null, ['class' => 'form-control', 'placeholder' => 'Código Proveedor SAP', 'id' => 'codigoProveedorSap', 'disabled']); !!}

              </div>
              <div class="col-md-3" id="pro_sap" style="display: none">

              </div>

              <div class="col-md-1 col-md-offset-1">
                  <span class="glyphicon glyphicon-import" aria-hidden="true" id="proveedorSap" style="cursor: pointer"></span>
              </div>              
          </div>
          <div class="row form-group">
                <div class="col-md-3">
                    {!! Form::label('ID_USERSAP', 'Código Usuario SAP') !!}
                </div>
                <div class="col-md-3" id="textoCodigo">
                    @if(isset($id_usersap))
                    {!! Form::text('id_usersap', $id_usersap, ['class' => 'form-control', 'placeholder' => 'Código Usuario', 'disabled']); !!}
                    @else
                    {!! Form::text('id_usersap', null, ['class' => 'form-control', 'placeholder' => 'Código Usuario', 'disabled']); !!}
                    @endif
                </div>
                <div class="col-md-3" id="selectCodigo" style="display: none"></div>
                <div class="col-md-1 col-md-offset-1">
                    <span class="glyphicon glyphicon-import" aria-hidden="true" id="codigoSap" style="cursor: pointer"></span>
                </div>
          </div>
          {!! Form::hidden('DESCRIPCION_PROVEEDORSAP', null, ['class' => 'form-control', 'id' => 'descripcion_proveedorsap']); !!}


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>
        <button type="submit" class="btn btn-default" style="border-color: white"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="font-size:32px; color: black;"></button>
      </div>
      {{--{!! Form::close() !!}
    </div>
  </div>
</div>  --}}
{{--

--}}


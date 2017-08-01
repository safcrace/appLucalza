<div class="panel panel-default">
    <div class="panel-heading panel-title" style="height: 65px">Empresas Asignadas
        <button type="button" class="btn btn-default" style="border-color: white; float: right" data-toggle="modal" data-target="#myModal"><a href="#" title="Agregar"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></a></button>
    </div>

    <div class="panel-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
            <th class="text-center">Empresa</th>
            <th class="text-center">Código Proveedor SAP</th>
            <th class="text-center">Anular</th>
            </thead>
            <tbody>

            @foreach ($empresas as $empresa)
                <tr>
                    <td>{{ $empresa->DESCRIPCION }}</td>
                    <td>{{ $empresa->CODIGO_PROVEEDOR_SAP }}</td>
                    <td class="text-center">
                      <a href="{{route('anular', $empresa->ID) }}"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                    </td>

                </tr>
            @endforeach


            </tbody>


        </table>

        <div class="text-center">
            {!! $empresas->render() !!}
        </div>
    </div>
</div>
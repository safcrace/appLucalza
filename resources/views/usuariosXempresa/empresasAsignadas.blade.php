<div class="panel panel-primary">
    <div class="panel-heading panel-title" style="height: 65px">Empresas Asignadas
        {{--  <button type="button" class="btn btn-default" style="border-color: white; float: right" id="nathalia"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size:32px; color: black"></span></button>  --}}
    </div>

    <div class="panel-body">

        <table class="table table-bordered table-striped table-hover">
            <thead>
            <th class="text-center">Empresa</th>
            <th class="text-center">Código Proveedor SAP</th>
            <th class="text-center">Código Usuario SAP</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Anular</th>
            </thead>
            <tbody>

            @foreach ($empresas as $empresa)
                <tr>
                    <td>{{ $empresa->DESCRIPCION }}</td>
                    <td>{{ $empresa->DESCRIPCION_PROVEEDORSAP }}</td>
                    <td>{{ $empresa->DESCRIPCION_USERSAPID }}</td>  
                    <td class="text-center">{{ ($empresa->ANULADO)?'ANULADO':'' }}</td>
                    <td class="text-center">
                      <a href="{{route('anularUsuarioEmpresa', $empresa->ID . '-' . $empresa->USER_ID) }}" class="btn-delete"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
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

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {       
        alert('Sender')
        $('.btn-delete').click(function (e) {
            alert('si');
            e.preventDefault();
            var row = $(this).parents('tr');
            var id = row.data('id');
            vurl = '{{ route('anularUsuarioEmpresa') }}';
            vurl = vurl.replace('%7Bid%7D', id);
            /*row.fadeOut();
            $('#myModal').modal('show');
            $('#revertir').click(function () {
                row.show();
            });
            $('#anular').click(function () {
                $('#myModal').modal('hide');*/
                alert(vurl)
                $.ajax({
                    type: 'get',
                    url: vurl,
                    success: function (data) {
                        location.reload();
                        /*if(data == 1) {
                            console.log('La Empresa se Desvinculo Exitosamente!!!.');
                            window.location.href = "/usuario/empresa";
                        } else {
                            alert('La Empresa no fue Desvinculada!!!');
                        }*/
                    }
                }).fail(function () {
                    alert ('La Empresa no pudo ser Desvinculada!!!');
                });
            //})
        });
        $('#nathalia').click(function() {            
            alert('Hello');
        })
    });
</script>
@endpush
<div class="panel panel-default">
    <div class="panel-heading panel-title">Vendedores Asignados</div>

    <div class="panel-body">

       <table class="table table-bordered table-striped table-hover">
         <thead>
           <th class="text-center">Nombre</th>
           <th class="text-center">Correo Electrónico</th>
           {{--<th class="text-center">Anular</th> --}}
         </thead>
         <tbody>

             @foreach ($users as $user)
                 <tr>
                     <td>{{ $user->nombre }}</td>
                     <td>{{ $user->email }}</td>
                     {{--<td class="text-center">
                       <a href="{{-- route('anular', $user->id) "><span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="font-size:20px; color: black"></span></a>
                     </td> --}}

                 </tr>
             @endforeach


         </tbody>


        </table>

        <div class="text-center">
          {!! $users->render() !!}
        </div>
    </div>
</div>

 @foreach( $clientes as $cli)

 <div class="card" style="width: 100%;">
     <ul class="list-group list-group-flush">
         <li class="list-group-item">
             <b> CI°:</b> &nbsp; {{$cli->CEDULA_RUC}} &nbsp; {{$cli->NOMBRE}}
             <div style="display: grid;grid-template-columns: 50% 50%;">
                 <a class="ml-3 mr-3 btn btn-sm btn-warning" style="color: black;" href="{{url('clientes/update').'/'.$cli->REGNRO}}">
                 EDITAR <i class="fas fa-edit"></i></a>
                 <a class="ml-3 mr-3  btn btn-sm btn-warning"  onclick="delete_row(event)" style="color: black;" href="{{url('clientes').'/'.$cli->REGNRO}}">
                 BORRAR <i class="fa fa-trash"></i></a>

             </div>

         </li>

     </ul>
 </div>


 @endforeach


 {{ $clientes->links('vendor.pagination.default') }}


 <script>
     async function delete_row(ev) {
         ev.preventDefault();
         if (!confirm("continuar?")) return;
         let req = await fetch(ev.currentTarget.href, {
             "method": "DELETE",
             headers: {

                 'Content-Type': 'application/x-www-form-urlencoded',
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             body: "_method=DELETE&_token=" + $('meta[name="csrf-token"]').attr('content')

         });
         let resp = await req.json();
         if ("ok" in resp) fill_grill();
         else alert(resp.err);

     }
 </script>
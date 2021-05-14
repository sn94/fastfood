<?php 
$MODULO_FLAG=    isset($_GET['m']) ? $_GET['m'] :  "";
$QUERY_FLAG=  $MODULO_FLAG!= "c"  ? ""  :    "?m=$MODULO_FLAG";

 
?>
<style>


table tbody tr td, table thead tr th{
    padding: 0px !important;
}
</style> 


 
 <table class="table bg-warning bg-gradient text-dark table-hover ">
     <thead style="font-family: mainfont;font-size: 18px;">
         <th></th>
         <th></th>
         <th>CÉDULA/RUC</th>
         <th>RAZÓN SOCIAL</th>
         <th>DOMICILIO</th>
         <th>CIUDAD</th>
         <th>TELÉF.</th>
         <th>CELULAR</th>
     </thead>

     <tbody>

         @foreach( $clientes as $cli)
         <tr>
             <td>
                 <a style="color: black;" href="{{url('clientes/update').'/'.$cli->REGNRO.$QUERY_FLAG}}"> <i class="fas fa-edit"></i></a>
             </td>
             <td>
                 <a onclick="delete_row(event)" style="color: black;" href="{{url('clientes').'/'.$cli->REGNRO.$QUERY_FLAG}}"> <i class="fa fa-trash"></i></a>
             </td>

             <td>{{$cli->CEDULA_RUC}}</td>
             <td>{{$cli->NOMBRE}}</td>
             <td>{{$cli->DIRECCION}}</td>
             <td>{{  is_null( $cli->ciudad_) ? "" :  $cli->ciudad_->ciudad}}</td>
             <td>{{$cli->TELEFONO}}</td>
             <td> {{$cli->CELULAR}}</td>

         </tr>
         @endforeach
     </tbody>

 </table>
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
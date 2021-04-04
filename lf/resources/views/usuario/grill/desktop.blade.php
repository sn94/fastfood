
 <?php

use App\Helpers\Utilidades;

?>

<style>


table tbody tr td, table thead tr th{
    padding: 0px !important;
}
</style> 

<table class="table bg-warning bg-gradient    text-dark">

    <thead style="font-family: mainfont;font-size: 18px;">
        <th></th>
        <th></th> 
        <th>CÉDULA</th> 
        <th>USUARIO</th>  
        <th>NOMBRE</th>
        <th>SUCURSAL</th>
    </thead>

    <tbody>

    @foreach( $usuarios as $prov)
        <tr>
            <td>
                <a style="color: black;" href="{{url('usuario/update').'/'.$prov->REGNRO}}"> <i class="fas fa-edit"></i></a>
            </td>
            <td>
            <a onclick="delete_row(event)" style="color: black;" href="{{url('usuario').'/'.$prov->REGNRO}}"> <i class="fa fa-trash"></i></a>
            </td>

            <td>{{$prov->CEDULA}}</td>
            <td>{{$prov->USUARIO}}</td> 
            <td>{{$prov->NOMBRES}}</td>
            <td>{{$prov->sucursal->DESCRIPCION}}</td>
           
            
        </tr>
    @endforeach
    </tbody>

</table> 

{{ $usuarios->links('vendor.pagination.default') }}



<script>
    
    async  function delete_row( ev ){
       ev.preventDefault();
        if( ! confirm("continuar?"))  return;
        let req = await fetch(  ev.currentTarget.href, {
            "method": "DELETE",
            headers: {
            
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: "_method=DELETE&_token="+$('meta[name="csrf-token"]').attr('content')
            
        });
        let resp = await req.json();
        if( "ok" in resp)    fill_grill(); 
        else alert( resp.err);
    
    }
</script>
 
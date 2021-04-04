<style>

table thead tr th,
    table tbody tr td {
        padding: 0px !important;

    }

</style>

<table class="table bg-warning bg-gradient    text-dark">

    <thead style="font-family: mainfont;font-size: 18px;">
        <th></th>
        <th></th> 
        <th>DESCRIPCIÓN</th>  
        <th></th>
    </thead>

    <tbody>

    @foreach( $sucursales as $prov)
        <tr>
            <td>
                <a onclick="event.preventDefault(); mostrar_form(this.href)" style="color: black;" href="{{url('sucursal/update').'/'.$prov->REGNRO}}"> <i class="fas fa-edit"></i></a>
            </td>
            <td>
            <a onclick="delete_row(event)" style="color: black;" href="{{url('sucursal').'/'.$prov->REGNRO}}"> <i class="fa fa-trash"></i></a>
            </td>

            
            <td>{{$prov->DESCRIPCION}}</td> 
            <td>{{$prov->MATRIZ=='S' ? 'MATRIZ'  :  '' }}</td> 
           
            
        </tr>
    @endforeach
    </tbody>

</table> 

{{ $sucursales->links('vendor.pagination.default') }}



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
 
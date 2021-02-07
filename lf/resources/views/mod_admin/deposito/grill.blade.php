 

<table class="table table-secondary text-dark">

    <thead style="font-family: mainfont;font-size: 18px;">
        <th></th>
        <th></th> 
        <th>DESCRIPCIÓN</th>  
        <th>ENTRADAS</th>
        <th>SALIDAS</th>
    </thead>

    <tbody>

    @foreach( $registros as $prov)
        <tr>
            <td>
                <a style="color: black;" href="{{url('cargo/update').'/'.$prov->REGNRO}}"> <i class="fa fa-pencil"></i></a>
            </td>
            <td>
            <a onclick="delete_row(event)" style="color: black;" href="{{url('cargo').'/'.$prov->REGNRO}}"> <i class="fa fa-trash"></i></a>
            </td>

            
            <td>{{$prov->DESCRIPCION}}</td> 
            <td></td>
            <td></td>
           
            
        </tr>
    @endforeach
    </tbody>

</table> 

{{ $registros->links('vendor.pagination.default') }}



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
 
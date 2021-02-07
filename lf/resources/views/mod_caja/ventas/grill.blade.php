 


<table class="table table-secondary text-dark">

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
                <a style="color: black;" href="{{url('clientes/update').'/'.$cli->REGNRO}}"> <i class="fa fa-pencil"></i></a>
            </td>
            <td>
            <a onclick="delete_row(event)" style="color: black;" href="{{url('clientes').'/'.$cli->REGNRO}}"> <i class="fa fa-trash"></i></a>
            </td>

            <td>{{$cli->CEDULA_RUC}}</td>
            <td>{{$cli->NOMBRE}}</td>
            <td>{{$cli->DIRECCION}}</td>
            <td>{{$cli->CIUDAD}}</td>
            <td>{{$cli->TELEFONO}}</td>
            <td> {{$cli->CELULAR}}</td>
            
        </tr>
    @endforeach
    </tbody>

</table> 
{{ $clientes->links('vendor.pagination.default') }}


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
 
@foreach( $proveedores as $prov)
<div class="card" style="width: 100%;">
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <b> RUC::</b> &nbsp; {{$prov->CEDULA_RUC}} &nbsp; {{$prov->NOMBRE}}
            <div style="display: grid;grid-template-columns: 50% 50%;">

                <a class="ml-3 mr-3 btn btn-sm btn-warning" style="color: black;" href="{{url('proveedores/update').'/'.$prov->REGNRO}}"> 
                EDITAR<i class="fas fa-edit"></i></a>

                <a class="ml-3 mr-3 btn btn-sm btn-warning" onclick="delete_row(event)" style="color: black;" href="{{url('proveedores').'/'.$prov->REGNRO}}">
                BORRAR <i class="fas fa-trash"></i></a>
            </div>

        </li>

    </ul>
</div>
@endforeach


{{ $proveedores->links('vendor.pagination.default') }}
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
 
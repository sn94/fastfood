 

<table class="table table-secondary text-dark">

    <thead style="font-family: mainfont;font-size: 18px;">
        
        <th>DESCRIPCIÓN</th>  
        <th>STOCK MÍN.</th>
        <th>STOCK MÁX.</th> 
        <th>ENTRADAS</th>
        <th>SALIDAS</th>
        <th>SALDO</th>
    </thead>


    <tbody>

    @foreach( $registros as $prov)
        <tr>
            
            @php 
            $edit_url="";
            if(  $CONTEXTO ==  "M")  $edit_url= url('materia-prima/update')."/".$prov->REGNRO;
            else   $edit_url= url('productos/update')."/".$prov->REGNRO;
            @endphp 

            <td>  <a style="color: black;" href="{{$edit_url}}"> {{$prov->DESCRIPCION}} </a>  </td> 
            <td> {{$prov->STOCK_MIN}} </td>
            <td> {{$prov->STOCK_MAX}}</td>
            <td> {{$prov->entradas}}</td>
            <td> {{$prov->salidas}}</td>
            <td>  {{  $prov->entradas -  $prov->salidas}}  </td>
            
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
 
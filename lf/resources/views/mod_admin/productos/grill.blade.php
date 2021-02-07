<?php

use App\Helpers\Utilidades;

?>

<table class="table table-secondary text-dark">

    <thead style="font-family: mainfont;font-size: 18px;">
        <tr>
            <th></th>
            <th></th>
            <th>CÓD. BARRA</th>
            <th>DESCRIPCIÓN</th>
            <th>VENTA</th>
            <th>COSTO</th>
            <th>PROVEEDOR</th>
            <th>STOCK TOT.</th>
        </tr>
    </thead>

    <tbody>

        @foreach( $productos as $prov)
        <tr>
            <td>
                <a style="color: black;" href="{{url('productos/update').'/'.$prov->REGNRO}}"> <i class="fa fa-pencil"></i></a>
            </td>
            <td>
                <a onclick="delete_row(event)" style="color: black;" href="{{url('productos').'/'.$prov->REGNRO}}"> <i class="fa fa-trash"></i></a>
            </td>

            <td>{{$prov->BARCODE}}</td>
            <td>{{$prov->DESCRIPCION}}</td>
            <td class="text-right">{{ Utilidades::number_f( $prov->PVENTA) }}</td>
            <td class="text-right">{{ Utilidades::number_f( $prov->PCOSTO) }}</td>
            <td>{{ (is_null($prov->proveedor)) ?  ''  :   $prov->proveedor->NOMBRE}}</td>
            <td> {{$prov->STOCKTOTAL}}</td>

        </tr>
        @endforeach
    </tbody>

</table>

{{ $productos->links('vendor.pagination.default') }}



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
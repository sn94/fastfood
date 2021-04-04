<?php

use App\Helpers\Utilidades;

?>


@foreach( $stock as $prov)



@php
$STOCK_ACTUAL= $prov->ENTRADAS + $prov->ENTRADA_PE + $prov->ENTRADA_RESIDUO -($prov->SALIDAS + $prov->SALIDA_VENTA);

@endphp

<div class="card" style="width: 100%;">


    <div class="card-body">

        <div class="row">
            <div class="col-6">
                <img style="width: 100%; height: auto;" src="{{url($prov->IMG)}}" alt="Sin Foto">
            </div>
            <div class="col-6 p-0">
                <p class="card-text">
                    <b style="font-size: 12.5px;color:black;">{{$prov->DESCRIPCION}}</b>
                    <span style="display:block;" class="badge badge-dark"> {{ Utilidades::number_f( $prov->PVENTA) }}</span>

                    <b style="font-size: 12px;display:block;">Cód.Barras: {{($prov->BARCODE=='') ? '****': $prov->BARCODE}}</b>


                    @if( $STOCK_ACTUAL<= 0 ) <span class="badge badge-danger">Sin stock </span>
                        @else
                        <span class="badge badge-success">Stock: {{ $STOCK_ACTUAL}} &nbsp; {{$prov->MEDIDA}}(s)</span>
                        @endif


                </p>

                @if( session("NIVEL") == "SUPER")
                <a class="btn btn-sm btn-warning p-0" style="color: black;" href="{{url('stock/update').'/'.$prov->REGNRO}}">
                    <i class="fas fa-edit"></i>EDITAR</a>
                <a class="btn btn-sm btn-warning p-0" onclick="delete_row(event)" style="color: black;" href="{{url('stock').'/'.$prov->REGNRO}}">
                    <i class="fa fa-trash"></i>BORRAR</a>
                @endif
            </div>
        </div>



    </div>
</div>
@endforeach


{{ $stock->links('vendor.pagination.default') }}



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
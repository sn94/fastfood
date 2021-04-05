@php

$sucursales= isset($datalist) ? $datalist : $sucursales;
@endphp


<style>
    table thead tr th,
    table tbody tr td {
        padding: 0px !important;

    }
</style>
<table class="table bg-warning bg-gradient    text-dark">

    <thead>
        <tr>
            <th></th>
            <th></th>
            <th>DESCRIPCIÓN</th>
            <th></th>
            <th>ORDEN</th>
        </tr>
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
            <td>{{$prov->ORDEN}}</td>


        </tr>
        @endforeach
    </tbody>

</table>
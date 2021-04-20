@php

use App\Models\Nota_pedido_detalles;
@endphp

<style>
    #PEDIDOS-TABLE thead tr th,
    #PEDIDOS-TABLE tbody tr td {
        padding: 0px !important;
    }

    #PEDIDOS-TABLE tbody tr td form {
        display: flex;
        flex-direction: row;

    }


    .table-success,
    .table-success>th,
    .table-success>td {
        background-color: #71d788 !important;
        font-weight: 600 !important;
    }



    /* style.css | http://localhost/fastfood/assets/css/style.css */

    .table-success,
    .table-success>th,
    .table-success>td {
        /* background-color: #c3e6cb; */
        background-color: #71d788 !important;
        font-weight: 600 !important;
        color: #555555 !important;
    }

    .table-warning,
    .table-warning>th,
    .table-warning>td {
        background-color: #f0d88e !important;
        font-weight: 600;
        color: #555555 !important;
    }
</style>



<table id="PEDIDOS-TABLE" class="table table-hover table-striped bg-warning">
    <thead class="thead-dark">
        <tr>
            <th>PEDIDO N°</th>
            <th>FECHA</th>
            <th>HORA</th>
            <th>DESCRIPCIÓN</th>
            <th>CANTIDAD</th>
            <th></th>

        </tr>
    </thead>

    <tbody class="text-dark">

        @foreach( $recibidos as $ven)
        <?php
        // dd($ven->stock->unidad_medida)
        ?>
        <tr>
            <td>{{$ven->NPEDIDO_ID}}</td>
            <td>{{$ven->pedido->created_at->format('d/m/Y')}}</td>
            <td>{{$ven->pedido->created_at->format('H:i')}}</td>
            <td>{{
           is_null($ven->stock)
           ? ''
           :
           $ven->stock->DESCRIPCION  
           }}</td>
            <td> 
            {{$ven->CANTIDAD.' '.$ven->stock->unidad_medida->UNIDAD}}
            </td>
            <td>
            <a onclick='mostrarFormAprobacion(event)' href="{{url('pedidos/aprobar/'.$ven->pedido->REGNRO)}}" class="btn btn-sm btn-success">APROBAR</a>
            </td>
        </tr>
        @endforeach


    </tbody>
</table>

{{ $recibidos->links('vendor.pagination.default') }}
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
            <th>ID</th>
            <th>CÓDIGO</th>
            <th>BARCODE</th>
            <th>DESCRIPCIÓN</th>
        
            <th>STOCK</th>
            <th></th> 

        </tr>
    </thead>

    <tbody class="text-dark">

        @foreach( $PRODUCTOS as $ven)

        @if( $ven->TIPO != "COMBO")
        

        <tr>

            <td>{{$ven->REGNRO}}</td>
            <td>{{$ven->CODIGO}}</td>
            <td>{{$ven->BARCODE}}</td>
            <td>{{$ven->DESCRIPCION}}</td>
            

            <td>
                {{$ven->CANTIDAD}}
                
                <span class="badge bg-success text-end">{{ $ven->MEDIDA}}</span>
            </td>

            <td>
                <a href="{{url('pedidos/create/'.$ven->REGNRO)}}" onclick="mostrar_form(event)" class="btn btn-sm btn-danger">PEDIR</a>
            </td>

            

        </tr>
        @endif
        @endforeach


    </tbody>
</table>


<x-pretty-paginator :datos="$PRODUCTOS" callback="fill_grill" />
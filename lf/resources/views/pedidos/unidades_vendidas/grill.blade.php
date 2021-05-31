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

@if( isset($print_mode))
@include("templates.print_report")
<h4>Unidades vendidas para {{ $fecha}} </h4>
@endif

<table id="PEDIDOS-TABLE" class="table table-hover table-striped fast-food-table">
    <thead class="thead-dark">
        <tr>
            <th>Id</th>
            <th>Código</th>
            <th>Barcode</th>
            <th>Descripción</th>
<th>Fecha Venta</th>
            <th>Vendido</th>
  
            @if( !isset($print_mode))
            <th></th>
            @endif

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
            <td>{{ $ven->FECHA->format('d/m/Y') }}</td>


            <td>
                {{$ven->CANTIDAD_VENDIDA}}
                <span class="badge bg-success text-end">{{ $ven->UNIDAD_MEDIDA}}</span>
            </td>
          
            @if( !isset($print_mode))
            <td>
                <a href="{{url('pedidos/create/'.$ven->REGNRO.'/'.$ven->CANTIDAD_VENDIDA)}}" onclick="mostrar_form(event)" class="btn btn-sm btn-danger">PEDIR</a>
            </td>
            @endif


        </tr>
        @endif
        @endforeach


    </tbody>
</table>


<x-pretty-paginator :datos="$PRODUCTOS" callback="fill_grill" />
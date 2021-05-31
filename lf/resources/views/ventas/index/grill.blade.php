<?php

use App\Helpers\Utilidades;

?>

<style>
    table thead tr th {
        padding: 0px !important;
    }
</style>


@if( isset($print_mode))
@include("templates.print_report")
<h4 style="text-align: center; margin-bottom: 1px;"> {{ $titulo}} </h4>
<h6 style="text-align: center; margin-top: 1px;">Cajero: {{$CAJERO->REGNRO.' - '. $CAJERO->NOMBRES}}</h6>
@endif



<table class="table table-hover table-striped fast-food-table">
    <thead class="thead-dark">
        <tr>
            @if( !isset($print_mode))
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            @endif
            <th>N° TICKET</th>
            <th>SESIÓN</th>
            <th>FECHA</th>
            <th>HORA</th>
            <th>CLIENTE</th>
            <th>TOTAL</th>
            <th>ESTADO</th>
        </tr>
    </thead>

    <tbody class="text-dark">

        @foreach( $VENTAS as $ven)

        <tr>
            @if( !isset($print_mode))
            <td><a onclick="anular(  event)" class="btn btn-danger btn-sm" href="{{url('ventas/anular/'.$ven->REGNRO)}}">ANULAR</a></td>
            <td><a onclick="imprimirTicket(<?= $ven->REGNRO ?>)" class="btn btn-danger btn-sm" href="#">RE-IMPRIMIR</a></td>
            <td><a onclick="enviarTicketPorEmail(event)" class="text-dark" href="<?= url("ventas/ticket/" . $ven->REGNRO) ?>"><i class="fas fa-envelope"></i></a></td>
            <td> <a href="{{url('ventas/view/'.$ven->REGNRO)}}" class="text-dark"> <i class="fas fa-eye"></i></a> </td>
            @endif

            <td>{{$ven->REGNRO}}</td>
            <td>{{$ven->SESION}}</td>
            <td>{{ Utilidades::fecha_f($ven->FECHA) }}</td>
            <td>{{ date("H:i:s", strtotime(  $ven->created_at )   )}}</td>
            <td>{{ is_null($ven->cliente) ? '' : $ven->cliente->NOMBRE . "(". $ven->cliente->CEDULA_RUC . ")"}}</td>
            <td>{{ $ven->TOTAL}}</td>
            <td>{{ $ven->ESTADO =='A'  ? 'ACTIVA'  : 'ANULADA'}}</td>
        </tr>
        @endforeach


    </tbody>
</table>




<x-pretty-paginator :datos="$VENTAS" callback="fill_grill" />

<script>
    async function anular(ev) {

        ev.preventDefault();
        let url_ = ev.target.href;

        let req = await fetch(url_);
        let resp = await req.json();
        if ("ok" in resp) {
            alert(resp.ok);
            fill_grill();
        } else alert(resp.err);

        //actualizar grill, mostrar estados
    }

    async function imprimirTicket(id_venta) {

        let idv = id_venta == undefined ? ultimoIdVentaRegistrado : id_venta;
        if (idv != undefined)
            printDocument.printFromUrl("<?= url("ventas/ticket") ?>/" + idv);

    }


    async function enviarTicketPorEmail(ev) {

        ev.preventDefault();
        let req = await fetch(ev.currentTarget.href, {
            headers: {
                formato: "email"
            }
        });
        let resp = await req.json();
        if ("ok" in resp)
            alert("Enviado!");
        else alert(resp.err);
    }
</script>
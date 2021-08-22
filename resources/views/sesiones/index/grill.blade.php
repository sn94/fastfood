<style>
    table thead tr th,
    table tbody tr td,
    table tfoot td {
        padding: 0px !important;
        padding-left: 2px !important;
        padding-right: 2px;
    }


    .text-end {
        text-align: right;
    }

    h4 {
        text-align: center;
    }
</style>

@if( isset( $print_mode ))

<style>
    table thead tr th,
    table tbody tr td {
        font-size: 11px;
    }
</style>

@include("templates.print_report")
<h4 style="text-align: center;">Sesiones de caja</h4>

@endif



<?php

$SESIONES =  isset($SESIONES) ? $SESIONES :  [];
?>


<div class="table-responsive" >
<table class="table table-hover table-striped text-dark fast-food-table table-responsive">
    <thead style="font-family: mainfont;font-size: 18px;">
        <tr>
            @if( !isset($print_mode) )
            <th></th>
            <th></th>
            @endif
            <th>Sucursal</th>
            <th>Sesión N°</th>
            <th>Apertura</th>
            <th>Cierre</th>
            <th>Caja</th>
            <th>Cajero</th>
            <th class="text-end">Monto Ini.</th>
            <th class="text-end">Tot.Efectivo</th>
            <th class="text-end">Tot.Tarjeta</th>
            <th class="text-end">Tot.Giros</th>
            <th class="text-end">Tot.Cheques</th>

        </tr>
    </thead>

    <tbody>

        @php
        $t_efe_ini= 0;
        $t_efectivo= 0;
        $t_tar= 0;
        $tot_giro= 0;
        $tot_cheque= 0;
        @endphp

        @foreach( $SESIONES as $sesion)
        @php
        $t_efe_ini += $sesion->EFECTIVO_INI;
        $t_efectivo += $sesion->TOTAL_EFE;
        $t_tar += $sesion->TOTAL_TAR;
        $tot_giro += $sesion->TOTAL_GIRO;
        $tot_cheque+= $sesion->TOTAL_CHEQUE;
        
        @endphp
       
        <tr>
            @if( !isset($print_mode) )
            <td>
                @if( $sesion->CAJERO == session("ID") || ( session("NIVEL") == "SUPER" ) )
                @if( $sesion->ESTADO == "A")
                <a href="{{url('sesiones/cerrar/'.$sesion->REGNRO)}}" class="btn btn-sm btn-danger">CERRAR</a>
                @else
                <a onclick="descargarArqueo(event)" href="{{url('sesiones/informe-arqueo/'.$sesion->REGNRO)}}" class="btn btn-sm btn-danger">ARQUEO</a>
                @endif
                @endif
            </td>
            <td>
                <a onclick="enviarArqueoPorEmail(event)" href="{{url('sesiones/informe-arqueo/'.$sesion->REGNRO)}}" class="text-dark fw-bold">
                    <i class="fa fa-envelope"></i>
                </a>
            </td>
            @endif

            <td>{{$sesion->SUCURSAL}}</td>

            <td>{{$sesion->REGNRO}}</td>
            <td>{{is_null( $sesion->FECHA_APE) ? "-"  :  $sesion->FECHA_APE->format('d/m/Y')}}</td>
            <td>{{ is_null($sesion->FECHA_CIE) ?  "-" : $sesion->FECHA_CIE->format('d/m/Y')}}</td>
            <td>{{$sesion->CAJA}}</td>
            <td>{{ is_null($sesion->cajero ) ? '' : $sesion->cajero->NOMBRES}} </td>
            <td class="text-end">{{$sesion->EFECTIVO_INICIAL}}</td>
            <td class="text-end">{{$sesion->TOTAL_EFECTIVO}}</td>
            <td class="text-end">{{$sesion->TOTAL_TARJETA}}</td>
            <td class="text-end">{{$sesion->TOTAL_GIROS}}</td>
            <td class="text-end">{{$sesion->TOTAL_CHEQUES}}</td>
        </tr>
        @endforeach
    </tbody>

    <tfoot>
        <td colspan="8"></td>
        <td class="text-end">{{ $t_efe_ini}}</td>
        <td class="text-end">{{ $t_efectivo}}</td>
        <td class="text-end">{{ $t_tar}}</td>
        <td class="text-end">{{ $tot_giro}}</td>
        <td class="text-end">{{ $tot_cheque}}</td>
    </tfoot>
</table>
</div>


<x-pretty-paginator :datos="$SESIONES" callback="buscarSesiones" />
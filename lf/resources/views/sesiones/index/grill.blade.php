<style>
    table thead tr th,
    table tbody tr td {
        padding: 0px !important;
        padding-left: 2px !important;
        padding-right: 2px;
    }


    .text-end {
        text-align: right;
    }
</style>


<?php

$SESIONES =  isset($datalist) ? $datalist :  $SESIONES;
?>
<table class="table table-hover table-striped text-dark bg-warning">



    <thead style="font-family: mainfont;font-size: 18px;">
        <tr>
            <th></th>
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
        </tr>
    </thead>

    <tbody>

        @php
        $t_efe_ini= 0;
        $t_efectivo= 0;
        $t_tar= 0;
        $tot_giro= 0;
        @endphp

        @foreach( $SESIONES as $sesion)
        @php
        $t_efe_ini= $sesion->EFECTIVO_INI_TOTAL;
        $t_efectivo= $sesion->TOTAL_EFE_TOTAL;
        $t_tar= $sesion->TOTAL_TAR_TOTAL;
        $tot_giro= $sesion->TOTAL_GIRO_TOTAL;
        @endphp
        <tr>
            <td>
                @if( $sesion->CAJERO == session("ID") || ( session("NIVEL") == "SUPER" ) )
                @if( $sesion->ESTADO == "A")
                <a href="{{url('sesiones/cerrar/'.$sesion->REGNRO)}}" class="btn btn-sm btn-danger">CERRAR</a>
                @else
                <a onclick="descargarArqueo(event)" href="{{url('sesiones/informe-arqueo/'.$sesion->REGNRO)}}" class="btn btn-sm btn-danger">ARQUEO</a>
                @endif
                @endif
            </td>
            <td>{{$sesion->SUCURSAL}}</td>

            <td>{{$sesion->REGNRO}}</td>
            <td>{{is_null( $sesion->FECHA_APE) ? "-"  :  $sesion->FECHA_APE->format('d/m/Y')}}</td>
            <td>{{ is_null($sesion->FECHA_CIE) ?  "-" : $sesion->FECHA_CIE->format('d/m/Y')}}</td>
            <td>{{$sesion->CAJA}}</td>
            <td>{{ is_null($sesion->cajero ) ? '' : $sesion->cajero->NOMBRES}} </td>
            <td class="text-end">{{$sesion->EFECTIVO_INI}}</td>
            <td class="text-end">{{$sesion->TOTAL_EFE}}</td>
            <td class="text-end">{{$sesion->TOTAL_TAR}}</td>
            <td class="text-end">{{$sesion->TOTAL_GIRO}}</td>
        </tr>
        @endforeach
    </tbody>

    <tfoot>
        <td colspan="7"></td>
        <td class="text-end">{{ $t_efe_ini}}</td>
        <td class="text-end">{{ $t_efectivo}}</td>
        <td class="text-end">{{ $t_tar}}</td>
        <td class="text-end">{{ $tot_giro}}</td>
    </tfoot>
</table>



<x-pretty-paginator :datos="$SESIONES" callback="buscarSesiones" />
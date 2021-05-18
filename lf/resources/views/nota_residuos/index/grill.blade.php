<style>
    table thead tr th,
    table tr td {
        padding: 0px 2px !important;
    }
</style>

<table id="SALIDA-TABLE" class="table table-striped table-hover bg-warning text-dark">
    <thead>
        <tr>
            <th>N°</th>
            <th>Fecha</th>

            <th>N° producción</th>
            <th>Registrado por</th>
            <th>Concepto</th>
        </tr>
    </thead>
    <tbody>

        @if( isset($NOTAS_RESIDUOS))
        @foreach( $NOTAS_RESIDUOS as $NOTAS_RESIDUOS_)
        <tr>
            <td>{{ $NOTAS_RESIDUOS_->NUMERO == ''  ? '****' :  $NOTAS_RESIDUOS_->NUMERO}}</td>
            <td>{{ is_null($NOTAS_RESIDUOS_->FECHA) ? '****':  $NOTAS_RESIDUOS_->FECHA->format('d/m/Y')}}</td>


            <td>{{$NOTAS_RESIDUOS_->PRODUCCION_ID == "" ? '****' :  $NOTAS_RESIDUOS_->PRODUCCION_ID  }}</td>
            <td>{{$NOTAS_RESIDUOS_->REGISTRADO_POR ==  "" ? "*****" : $NOTAS_RESIDUOS_->REGISTRADO_POR  }} </td>
            <td> {{$NOTAS_RESIDUOS_->CONCEPTO == ""  ?  "*********" :   $NOTAS_RESIDUOS_->CONCEPTO }}  </td>
        </tr>
        @endforeach
        @endif


    </tbody>
</table>
 
<x-pretty-paginator :datos="$NOTAS_RESIDUOS" callback="fill_grill" />
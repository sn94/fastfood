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
            <th>Autorizado por</th>
            <th>Concepto</th>
        </tr>
    </thead>
    <tbody>

        @if( isset($REMISION))
        @foreach( $REMISION as $REMISION_)
        <tr>
            <td>{{ $REMISION_->NUMERO == ''  ? '****' :  $REMISION_->NUMERO}}</td>
            <td>{{ is_null($REMISION_->FECHA) ? '****':  $REMISION_->FECHA->format('d/m/Y')}}</td>


            <td>{{$REMISION_->PRODUCCION_ID == "" ? '****' :  $REMISION_->PRODUCCION_ID  }}</td>
            <td>{{$REMISION_->AUTORIZADO_POR }} </td>
            <td> {{$REMISION_->CONCEPTO == ""  ?  "*********" :   $REMISION_->CONCEPTO }}  </td>
        </tr>
        @endforeach
        @endif


    </tbody>
</table>

<x-pretty-paginator :datos="$REMISION" callback="fill_grill" />
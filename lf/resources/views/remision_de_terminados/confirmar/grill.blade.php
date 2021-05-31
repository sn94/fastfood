<style>
    table thead tr th,
    table tr td {
        padding: 0px 2px !important;
    }
</style>

<table id="REMISION-TABLE" class="table table-striped table-hover fast-food-table text-dark">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th>Id</th>
            <th>N°</th>
            <th>Fecha</th>

            <th>N° producción</th>
            <th>Autorizado por</th>
            <th>Concepto</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>

        @if( isset($REMISION))

        @foreach( $REMISION as $REMISION_)

        <tr>

            <td>
                @if( $REMISION_->ESTADO == "P" )
                <a onclick="confirmarRecepcion(event);" class="btn btn-sm btn-success" href="{{url('remision-prod-terminados/confirmar').'/'.$REMISION_->REGNRO}}"> Confirmar</a>
                @endif
            </td>
            <td>
                <a onclick="verDetalles(event)" class="text-dark" href="{{url('remision-prod-terminados/view').'/'.$REMISION_->REGNRO}}"> <i class="fa fa-eye"></i> </a>
            </td>


            <td>{{ $REMISION_->REGNRO  }}</td>
            <td>{{ $REMISION_->NUMERO == ''  ? '' :  $REMISION_->NUMERO}}</td>
            <td>{{ is_null($REMISION_->FECHA) ? '':  $REMISION_->FECHA->format('d/m/Y')}}</td>


            <td>{{$REMISION_->PRODUCCION_ID == "" ? '' :  $REMISION_->PRODUCCION_ID  }}</td>
            <td>{{$REMISION_->AUTORIZADO_POR }} </td>
            <td> {{$REMISION_->CONCEPTO == ""  ?  '' :   $REMISION_->CONCEPTO }} </td>
            <td class=" {{$REMISION_->ESTADO == 'P'  ?  'text-danger fw-bold' :   'text-success fw-bold' }}">
                {{$REMISION_->ESTADO == "P"  ?  'Pendiente' :   'Aceptada' }}
            </td>
        </tr>
        @endforeach
        @endif


    </tbody>
</table>

<x-pretty-paginator :datos="$REMISION" callback="buscarNotaRemision" />


<script>
    async function confirmarRecepcion(ev) {
        ev.preventDefault();
        if (!confirm("confirmar recepción de productos elaborados?")) return;
        let req = await fetch(ev.currentTarget.href);
        let resp = await req.json();
        if ("ok" in resp) {
            alert(resp.ok)
            buscarNotaRemision();
        } else alert(resp.err);

    }




    async function verDetalles(ev) {

        ev.preventDefault();

        let req = await fetch(ev.currentTarget.href);
        let resp = await req.text();
        $("#NOTA-REMISION-MODAL  .modal-body ").html(resp);
        $("#NOTA-REMISION-MODAL").modal("show");
    }
</script>
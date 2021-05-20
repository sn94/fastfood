<style>
    table thead tr th,
    table tr td {
        padding: 0px 2px !important;
    }
</style>


@if( isset( $print_mode ))
@include("templates.print_report")
<h4  style="text-align: center;">Órdenes de producción</h4>
@endif
<table id="FP-TABLE" class="table table-striped table-hover bg-warning text-dark">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th>Id</th>
            <th>Número</th>
            <th>Fecha</th> 
            <th>Elaborado por</th>
        </tr>
    </thead>
    <tbody>

        @if( isset($FICHAS_PRODUCCION))
        @foreach( $FICHAS_PRODUCCION as $FICHAS_PRODUCCION_)
        <tr>

            <td>
                <a style="color: black;" href="{{url('ficha-produccion/update').'/'.$FICHAS_PRODUCCION_->REGNRO}}"> <i class="fas fa-edit"></i></a>
            </td>
            <td>
                <a onclick="delete_row(event)" style="color: black;" href="{{url('ficha-produccion').'/'.$FICHAS_PRODUCCION_->REGNRO}}"> <i class="fa fa-trash"></i></a>
            </td>

            <td>{{ $FICHAS_PRODUCCION_->REGNRO }}</td>

            <td>{{ $FICHAS_PRODUCCION_->NUMERO == ''  ? '****' :  $FICHAS_PRODUCCION_->NUMERO}}</td>
            <td>{{ is_null($FICHAS_PRODUCCION_->FECHA) ? '****':  $FICHAS_PRODUCCION_->FECHA->format('d/m/Y')}}</td>

            <td>{{$FICHAS_PRODUCCION_->ELABORADO_POR ==  "" ? "*****" : $FICHAS_PRODUCCION_->ELABORADO_POR  }} </td>

        </tr>
        @endforeach
        @endif


    </tbody>
</table>
<script>
    async function delete_row(ev) {
        ev.preventDefault();
        if (!confirm("continuar?")) return;
        let req = await fetch(ev.currentTarget.href, {
            "method": "DELETE",
            headers: {

                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: "_method=DELETE&_token=" + $('meta[name="csrf-token"]').attr('content')

        });
        let resp = await req.json();
        if ("ok" in resp) fill_grill();
        else alert(resp.err);

    }
</script>
<x-pretty-paginator :datos="$FICHAS_PRODUCCION" callback="fill_grill" />
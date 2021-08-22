<style>
    table thead tr th, table tbody tr td{
padding: 0px !important;
padding-left: 2px !important;
padding-right: 2px;
    }

.text-center{
    text-align: center;
}
    .text-end{
        text-align: right;
    }
</style>
<table class="table fast-food-table bg-gradient    text-dark">

    <thead style="font-family: mainfont;font-size: 18px;">
        <tr>
            <th></th>
            <th></th>
            <th>ID</th>
            <th>Descripción</th>
            <th class="text-end">Costo</th>
            <th class="text-center">ORDEN</th>
        </tr>
    </thead>

    <tbody>

        @foreach( $servicios as $prov)
        <tr>
            <td>
                <a  onclick="edit_row(event)" style="color: black;" href="{{url('servicios/update').'/'.$prov->REGNRO}}"> <i class="fas fa-edit"></i></a>
            </td>
            <td>
                <a onclick="delete_row(event)" style="color: black;" href="{{url('servicios').'/'.$prov->REGNRO}}"> <i class="fa fa-trash"></i></a>
            </td>


            <td>{{$prov->REGNRO}}</td>
            <td>{{$prov->DESCRIPCION}}</td>
            <td class="text-end">{{$prov->COSTO}}</td>
            <td class="text-center">{{$prov->ORDEN}}</td>


        </tr>
        @endforeach
    </tbody>

</table>

{{ $servicios->links('vendor.pagination.default') }}



<script>
    async function edit_row(ev) {
        ev.preventDefault();

        let req = await fetch(ev.currentTarget.href);
        let resp = await req.text();
        $("#form").html(resp);
        formatoNumerico.formatearCamposNumericosDecimales();

    }
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
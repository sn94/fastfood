<style>
    table thead tr th, table tbody tr td{
padding: 0px !important;
padding-left: 2px !important;
padding-right: 2px;
    }
</style>
<table class="table bg-warning bg-gradient    text-dark">

    <thead style="font-family: mainfont;font-size: 18px;">
        <tr>
            <th></th>
            <th></th>
            <th>ID</th>
            <th>DESCRIPCIÓN</th>
            <th>ORDEN</th>
        </tr>
    </thead>

    <tbody>

        @foreach( $cargos as $prov)
        <tr>
            <td>
                <a  onclick="edit_row(event)" style="color: black;" href="{{url('cargo/update').'/'.$prov->REGNRO}}"> <i class="fas fa-edit"></i></a>
            </td>
            <td>
                <a onclick="delete_row(event)" style="color: black;" href="{{url('cargo').'/'.$prov->REGNRO}}"> <i class="fa fa-trash"></i></a>
            </td>


            <td>{{$prov->REGNRO}}</td>
            <td>{{$prov->DESCRIPCION}}</td>
            <td>{{$prov->ORDEN}}</td>


        </tr>
        @endforeach
    </tbody>

</table>

{{ $cargos->links('vendor.pagination.default') }}



<script>
    async function edit_row(ev) {
        ev.preventDefault();

        let req = await fetch(ev.currentTarget.href);
        let resp = await req.text();
        $("#form").html(resp);

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
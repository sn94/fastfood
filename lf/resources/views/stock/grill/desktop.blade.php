@if( session("NIVEL") == "SUPER")
<style>
    table thead tr th:nth-child(1),
    table tbody tr td:nth-child(1) {
        padding-left: 2px;
        width: 3%;
    }

    table thead tr th:nth-child(2),
    table tbody tr td:nth-child(2) {
        width: 3%;
    }
</style>
@endif

<style>
    table thead tr th,
    table tbody tr td {
        padding: 0px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        padding-left: 3px !important;
        padding-right: 3px !important;
    }
</style>

<table class="table bg-warning bg-gradient    text-dark">

    <thead style="font-family: mainfont;">
        <tr>
            @if( session("NIVEL") == "SUPER")
            <th></th>
            <th></th>
            @endif
            <th>CÓDIGO</th>
            <th>CÓD. BARRA</th>
            <th><a href="#" onclick="ordenarDescripcion('ASC')"> <img src="<?= url('assets/icons/up_icon.png') ?>" alt="Asc"></a> DESCRIPCIÓN
                <a href="#" onclick="ordenarDescripcion('DESC')"><img src="<?= url('assets/icons/down_icon.png') ?>"></a>
            </th>
            <th class="text-end">STOCK TOT.</th>
            <th class="text-end"><a href="#" onclick="ordenarPventa('ASC')"> <img src="<?= url('assets/icons/up_icon.png') ?>" alt="Asc"></a> VENTA
                <a href="#" onclick="ordenarPventa('DESC')"><img src="<?= url('assets/icons/down_icon.png') ?>"></a>
            </th>
        </tr>
    </thead>

    <tbody>

        @foreach( $stock as $prov)

        @include("stock.grill.desktop_item")
        @endforeach
    </tbody>

</table>

{{ $stock->links('vendor.pagination.default') }}



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
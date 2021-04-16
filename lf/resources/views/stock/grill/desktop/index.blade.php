@if( session("NIVEL") == "SUPER"  ||  session("NIVEL") == "GOD" )
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
            @if( session("NIVEL") == "SUPER" ||  session("NIVEL") == "GOD" )
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

        @include("stock.grill.desktop.item")
        @endforeach
    </tbody>

</table>

{{ $stock->links('vendor.pagination.default') }}


 
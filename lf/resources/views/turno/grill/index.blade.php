<style>
    table thead tr th,
    table tbody tr td {
        padding: 0px !important;
        padding-left: 2px !important;
        padding-right: 2px;
    }

    thead {
        font-family: mainfont;
        font-size: 18px;
    }
</style>

@include("turno.grill.base")

{{ $turnos->links('vendor.pagination.default') }}



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
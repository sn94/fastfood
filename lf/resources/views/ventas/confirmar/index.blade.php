<form action="<?= url("ventas/confirmar") ?>" method="POST" onsubmit="confirmarVenta(event)">
    <label for="">Medio de pago:</label>
    <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
    <x-forma-pago-chooser name="FORMA" value="" id="" callback="" style="" class="select-form" />
    <button class="btn btn-sm fast-food-form-button">Ok</button>
</form>


<script>
    async function confirmarVenta(ev) {
        //config_.processData= false; config_.contentType= false;

        ev.preventDefault();

        let req = await fetch(ev.target.action, {
            "method": "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            },
            body: $(ev.target).serialize()
        });
        let resp = await req.json();
        if ("ok" in resp) {
            alert(resp.ok);
            $("#CONFIRMAR-VENTA").modal("hide");
            fill_grill();
        } else {

            alert(resp.err);
        }
    }
</script>
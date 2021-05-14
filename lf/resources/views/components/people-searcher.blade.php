<?php

$randomId = random_int(100, 999);
$searcherId =  "modal_" . $randomId;

$url =  $tipo == "CLIENTE" ?  url("clientes/buscar")  :  url("proveedores/buscar");
?>

<div>
    <x-fast-food-modal id="{{$searcherId}}" title="Buscador de {{tipo}}">

        <input class="search" type="text" placeholder="BUSCAR POR NOMBRE, O CÉDULA, O RUC" oninput="buscar<?= $searcherId ?>( this)">
        <table class="BUSCADOR-TABLE  table  table-striped table-hover">
            <thead>
                <tr>
                    <th>CÉDULA</th>
                    <th>NOMBRES</th>
                </tr>
            </thead>
            <tbody>


            </tbody>
    </x-fast-food-modal>
</div>

<script>
    window.<?= $searcherId ?> = {

      seleccionar:  function(ev) {

let elegido = String(ev.currentTarget.id);

let modelo = buscador_people_data_model.filter((ar) => String(ar.REGNRO) == elegido);
if (modelo.length > 0) {
    buscador_people_modelo = modelo[0];
    $(".modal.show").modal("hide");
    //Montar los valores
    $(window.buscador_people_target.KEY).val(buscador_people_modelo.REGNRO);
    $(window.buscador_people_target.NAME).val(buscador_people_modelo.NOMBRE);

}
},
        buscar: async function(esto) {

            let td_const = function(ar) {
                return "<td class='p-0'>" + ar + "</td>";
            };

            let tr_const = function(row) {
                let claves = Object.keys(row);
                let tds = claves.map((cla) => td_const(row[cla]));
                let id_row = row.REGNRO;
                let evento ="sleciconado";

                return "<tr  " + evento + " id='" + id_row + "' class='buscador-row'>" + tds + "</tr>";
            };

            let termino = esto == undefined ? "" : esto.value;
            //Seleccionar url

            let grill_url = "<?$url?>";

            let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
            $("#<?= $searcherId ?> table tbody").html(loader);

            /***Request */
            /**Header */
            let header_req = {
                'X-Requested-With': "XMLHttpRequest",
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'formato': 'json'
            };
            /**Si se busca un producto, filtrar solo los de tipo PARA VENTA */
            let body_req = "buscado=" + termino + "";
            // if( modoBusqueda == "P")   body_req= body_req+"&tipo=P";
            /**Sending request */
            let req = await fetch(grill_url, {

                method: "POST",
                headers: header_req,
                body: body_req
            });
            /**Response is coming */
            buscador_people_data_model = await req.json();


            let html__ = buscador_people_data_model.map((ar) => {
                return {
                    REGNRO: ar.REGNRO,
                    CEDULA_RUC: ar.CEDULA_RUC,
                    NOMBRE: ar.NOMBRE
                };
            }).map(tr_const);

            $("#<?= $searcherId ?> table tbody").html(html__);
        }

    };


    async function buscar<?= $searcherId ?>(esto) {

        let td_const = function(ar) {
            return "<td class='p-0'>" + ar + "</td>";
        };
        let tr_const = function(row) {
            let claves = Object.keys(row);
            let tds = claves.map((cla) => td_const(row[cla]));
            let id_row = row.REGNRO;
            let evento = function(ev) {

                let elegido = String(ev.currentTarget.id);

                let modelo = buscador_people_data_model.filter((ar) => String(ar.REGNRO) == elegido);
                if (modelo.length > 0) {
                    buscador_people_modelo = modelo[0];
                    $(".modal.show").modal("hide");
                    //Montar los valores
                    $(window.buscador_people_target.KEY).val(buscador_people_modelo.REGNRO);
                    $(window.buscador_people_target.NAME).val(buscador_people_modelo.NOMBRE);

                }
            };

            return "<tr  " + evento + " id='" + id_row + "' class='buscador-row'>" + tds + "</tr>";
        };

        let termino = esto == undefined ? "" : esto.value;
        //Seleccionar url

        let grill_url = "<?$url?>";

        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#<?= $searcherId ?> table tbody").html(loader);

        /***Request */
        /**Header */
        let header_req = {
            'X-Requested-With': "XMLHttpRequest",
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'formato': 'json'
        };
        /**Si se busca un producto, filtrar solo los de tipo PARA VENTA */
        let body_req = "buscado=" + termino + "";
        // if( modoBusqueda == "P")   body_req= body_req+"&tipo=P";
        /**Sending request */
        let req = await fetch(grill_url, {

            method: "POST",
            headers: header_req,
            body: body_req
        });
        /**Response is coming */
        buscador_people_data_model = await req.json();


        let html__ = buscador_people_data_model.map((ar) => {
            return {
                REGNRO: ar.REGNRO,
                CEDULA_RUC: ar.CEDULA_RUC,
                NOMBRE: ar.NOMBRE
            };
        }).map(tr_const);

        $("#<?= $searcherId ?> table tbody").html(html__);
    }
</script>
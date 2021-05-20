<script>
    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }


    function drop(ev) {
        ev.preventDefault();


        let TARGET = ev.target.tagName.toLowerCase() == "button" ? ev.target.parentNode :
            (ev.target.tagName.toLowerCase() == "input" ? ev.target.parentNode.parentNode : ev.target);
        console.log("TARGET", TARGET);

        var data = ev.dataTransfer.getData("text");
        let familyID = data.split("-")[1];

        let a_acoplar = document.getElementById(data);
        TARGET.appendChild(a_acoplar);
        /*
                if (ev.target.tagName.toLowerCase() == "button")
                    ev.target.parentNode.appendChild(a_acoplar);
                else {
                    ev.target.appendChild(a_acoplar);
                }*/



        document.getElementById("FAMILY-" + familyID).value = TARGET.id.split("-")[1];

        //Elemento a mudar si ya existia otro en el contenedor
        let a_mudar = Array.prototype.filter.call(TARGET.children, function(ar) {
            return (ar.id != data);
        });
        console.log(a_mudar)
        if (a_mudar.length > 0) {

            //buscar paneles libres
            let paneles = document.querySelectorAll("#DRAGGABLE-BUTTONS div[id*=panel]");
            let panel_libre = Array.prototype.filter.call(paneles, function(ar) {
                return (ar.children.length == 0);
            });
            if (panel_libre.length > 0) {
                let posicionPanelLibre = panel_libre[0].id.split("-")[1];
                panel_libre[0].appendChild(a_mudar[0]);
                document.getElementById("FAMILY-" + a_mudar[0].id.split("-")[1]).value = posicionPanelLibre;
            }
        }
    }

    function allowDrop(ev) {
        ev.preventDefault();
    }
</script>
<style>
     @media only screen and (max-width: 600px) {
        #DRAGGABLE-BUTTONS {
           
            display: grid;
            grid-template-columns: auto auto ;
            grid-template-rows:  auto auto auto auto auto auto auto auto auto auto auto auto ;

        }
    }
    /* Small devices (portrait tablets and large phones, 600px and up) */
    @media only screen and (min-width: 600px) {
        #DRAGGABLE-BUTTONS {
            display: grid;
            grid-template-columns: auto auto  auto;
            grid-template-rows:  auto auto auto auto auto auto auto auto ;
        }
    }

    @media only screen and (min-width: 768px) {
        #DRAGGABLE-BUTTONS {
            display: grid;
            grid-template-columns: auto auto auto auto auto;
            grid-template-rows: auto auto auto auto auto;
        }
    }

    @media only screen and (min-width: 992px) {
        #DRAGGABLE-BUTTONS {
            display: grid;
            grid-template-columns: auto auto auto auto auto;
            grid-template-rows: auto auto auto auto auto;
        }
    }




    #DRAGGABLE-BUTTONS {
        padding: 0px;
        border: 5px solid var(--color-1);
        background-color: wheat;
    }





    .posicion-boton {

        height: auto;
        width: auto;
        border: 1px solid black;
        font-size: 11px;
        font-weight: 600;
    }

    .posicion-boton button {
        width: 100%;
        background-color: var(--color-4);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .posicion-boton button input {
        text-align: center;
        color: white;
        font-size: 12.5px;
        border: none;
        width: 20px;
        font-weight: 600;
        border-radius: 30px;
        background: black;
    }
</style>

<div class="row">
    <div class="col-12">
        <label style="font-family: Arial, Helvetica, sans-serif; color: white;font-weight: 600;" for="">ARRASTRA A LA POSICIÓN DESEADA (ESTA DISPOSICIÓN SE APLICARÁ EN LA PANTALLA DE VENTAS)</label>
        <div id="DRAGGABLE-BUTTONS" class="container-fluid m-0">

            @php
            $posicion= 1;
            @endphp
            @foreach( $familias as $fami)


            <div id="panel-{{$posicion}}" class="posicion-boton" ondrop="drop(event)" ondragover="allowDrop(event)">
                <button id="btn-{{$fami->REGNRO}}" type="button" draggable="true" ondragstart="drag(event)">
                    <input readonly type="text" id="FAMILY-{{$fami->REGNRO}}" value="{{$posicion}}">
                    {{$fami->DESCRIPCION}}
                </button>
            </div>
            @php
            $posicion++;
            @endphp
            @endforeach
        </div>
        <div>
            <button onclick="guardarPosiciones()" class="btn btn-sm btn-success" type="button">GUARDAR POSICIONES</button>
        </div>
    </div>
</div>


<script>
    //Guardar posicion


    function obtenerPosiciones() {
        let familias_input = document.querySelectorAll("input[id*=FAMILY]");

        let info_posiciones = Array.prototype.map.call(familias_input, function(fam) {

            let regnro = fam.id.split("-")[1];
            let posicion = fam.value;
            return {
                REGNRO: regnro,
                NRO_PESTANA: posicion
            };
        });

        return info_posiciones;
    }

    async function refrescarPanelPosiciones() {
        let req = await fetch("<?= url("familia/posiciones") ?>");
        let resp = await req.text();
        $("#BUTTONPOSICION").html(resp);
    }
    async function guardarPosiciones() {
        //config_.processData= false; config_.contentType= false;

        show_loader();
        let req = await fetch("<?= url("familia/posiciones") ?>", {
            "method": "POST",

            headers: {
                'Content-Type': "application/json",
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            },
            body: JSON.stringify(obtenerPosiciones())
        });
        let resp = await req.json();
        hide_loader();
        if ("ok" in resp) {

            await refrescarPanelPosiciones();

            fill_grill();
        } else {
            alert(resp.err);
        }
    }
</script>
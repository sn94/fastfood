 


<div class="row bg-dark "  id="RESIDUOS-FIELDS">

    <div class="col-12 col-md-8  mb-1">
        <div style="display: flex; flex-direction: row;">


            <label style="  font-size: 20px;  ">ITEM:</label>
            <a href="#" onclick="buscarResiduo()"><i class="fa fa-search"></i></a>
            <input size="5" style="width:50px !important; font-weight: 600; color: black; " class="form-control ITEM-ID" type="text" id="ITEM-ID" disabled>

            <input disabled class="form-control ITEM" style="width: 100% !important; height: 40px;" autocomplete="off" type="text" id="ITEM">

        </div>
    </div>

    <div class="col-12  col-md-4  mb-1 pl-0" style="display: flex;  flex-direction: row;">

        <label>CANTIDAD:&nbsp; </label>
        <div style="display: flex; flex-direction: column;">
            <input onkeydown="if(event.keyCode==13) {event.preventDefault(); cargar_tabla();}" style="width: 90px !important;" id="CANTIDAD" class="form-control CANTIDAD decimal" type="text" />
            <label class="MEDIDA" style="letter-spacing: 2px; font-weight: 600; color: yellow;" id="MEDIDA"></label>
        </div>
        <a href="#" onclick="cargar_tabla()"><i class="fa fa-download"></i></a>

    </div>




</div>

<div class="row bg-dark mt-2 pt-1 pb-2 pr-2 pl-2 pr-md-2 pl-md-2">
    <div class="col-12 col-md-12 p-0">
        <table class="table table-striped table-secondary text-dark">

            <thead>
                <tr style="font-family: mainfont;font-size: 18px;">
                <th>CÓDIGO</th>
                    <th>DESCRIPCIÓN</th>
                    <th>UNI.MED.</th>
                    <th> CANTIDAD</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="RESIDUOS-DETALLE">

            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">
                    </th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>



</div>




<script>
    var residuos_model = [];


    //ver

    async function buscarResiduo() {

        let PANEL_ID = "#RESIDUOS-FIELDS";
            window.buscar_items_target = function({
                REGNRO,
                DESCRIPCION,
                MEDIDA
            }) {

                $(PANEL_ID + " .ITEM-ID").val(REGNRO);
                $(PANEL_ID + " .ITEM").val(DESCRIPCION);
                $(PANEL_ID + " .MEDIDA").text(MEDIDA);
            };

            if ("buscar_items__" in window) {
                $("#BUSCADOR-TIPO-STOCK").val("MP");
                await buscar_items__();
            } else {
                let req = await fetch("<?= url('buscador-items') ?>/MP" );
                let resp = await req.text();
                $("body").append(resp);
                await buscar_items__();
            }
    }

    //Autocomplete
    async function autocompletado_items() {
        let url_ = $("#RESOURCE-URL").val();
        let req = await fetch(url_, {
            method: "POST",

            headers: {
                formato: "json",
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: "tipo=P" /** preventa parametro solo valido para buscar productos */
        });
        let resp = await req.json();

        var dataArray = resp.map(function(value) {
            return {
                label: value.DESCRIPCION,
                value: value.REGNRO
            };
        });

        let elementosTARGET = document.getElementById("ITEM");

        new Awesomplete(elementosTARGET, {
            list: dataArray,
            // insert label instead of value into the input.
            replace: function(suggestion) {
                this.input.value = suggestion.label;
                $("#ITEM-ID").val(suggestion.value);
                mostrar_item(suggestion.value);
            }
        });

    }






    function deleteme(esto) {

        let row = $(esto.parentNode.parentNode);
        let id = row.attr("id");
        $(esto.parentNode.parentNode).remove();
        //quitar del modelo 
        residuos_model = residuos_model.filter(function(arg) {


            return String(arg.ITEM) != String(id);
        });
    }


    function limpiar_campos_detalle() {
        $("#ITEM-ID").val("");
        $("#ITEM").val("");
        $("#CANTIDAD").val("");
        $("#MEDIDA").text("");
    }


    function limpiar_tabla() {
        $("#RESIDUOS-DETALLE").html("");
    }

    //actualizar tabla html
    function actualizar_fila(objec) {
        let existe = residuos_model.map((ar) => ar.ITEM).filter((ar) => parseInt(ar) == parseInt(objec.ITEM)).length;
        if (existe > 0) {
            let numero_de_filas = $("#RESIDUOS-DETALLE tr").length;
            if (numero_de_filas == 0) return false;

            for (let nf = 0; nf < numero_de_filas; nf++) {
                let lafila = $("#RESIDUOS-DETALLE tr")[nf];

                let esQueTipoStock = $(lafila).hasClass(objec.TIPO + "-class");

                if (String(lafila.id) == String(objec.ITEM) && esQueTipoStock) {
                    $(lafila).remove();
                    break;
                }
            }
        }
    }

    function actualiza_modelo_de_datos(NuevoObjeto) {

        let EXISTE = residuos_model.filter(
            (ar) => {
                return (ar.TIPO == NuevoObjeto.TIPO && ar.ITEM == String(NuevoObjeto.ITEM));

            }
        ).length;
        if (EXISTE == 0) {
            residuos_model.push(NuevoObjeto);
            return;
        }

        residuos_model = residuos_model.map(
            (ar) => {

                if (ar.TIPO == NuevoObjeto.TIPO && ar.ITEM == String(NuevoObjeto.ITEM)) {
                    let obj_act = ar;
                    obj_act.CANTIDAD = parseFloat(NuevoObjeto.CANTIDAD);
                    return obj_act;
                }
                return ar;
            }
        );



    }




    function cargar_tabla() {
        //if( Object.keys(buscador_items_modelo).length == 0  ) return;

        let regnro = $("#ITEM-ID").val();
        let coditem= buscador_items_modelo.CODIGO;
        let descri = $("#ITEM").val();
        let cantidad = $("#CANTIDAD").val();
        let medida = $("#MEDIDA").text();


        /**Lista de elementos coincidentes en el modelo */
        let modeloDeItemSeleccionado = residuos_model.filter(
            (ar) => {
                return (ar.TIPO == buscador_items_modelo.TIPO && ar.ITEM == String(buscador_items_modelo.REGNRO));
            }
        );
        //let canti = modeloDeItemSeleccionado.length > 0 ? modeloDeItemSeleccionado[0].CANTIDAD : 1;

        if (regnro != "") {

            let codite= "<td> " + coditem + "</td>";
            let des = "<td> " + descri + "</td>";
            let med = "<td> " + medida + "</td>";
            let cant = "<td>" + cantidad + "</td>";
            let del = "<td> <a style='color:black;' href='#' onclick='deleteme( this )'> <i class='fa fa-trash'></i> </a>  </td>";

            //agregar al modelo
            let tipoStock = buscador_items_modelo.TIPO;
            let objc = {
                CODIGO: coditem,
                ITEM: String(regnro),
                CANTIDAD: formValidator.limpiarNumero(cantidad),
                TIPO: tipoStock,
                MEDIDA: medida
            };

            let classIdentTipoItem = tipoStock + "-class";

            let nueva_tr = "<tr  class='" + classIdentTipoItem + "' id='" + regnro + "' >" + codite+ des + med + cant + del + "</tr>";

            //Actualizar modelo de datos
            actualiza_modelo_de_datos(objc);

            actualizar_fila(objc); //Quitar de la tabla para actualizar la fila


            $("#RESIDUOS-DETALLE").append(nueva_tr);
            limpiar_campos_detalle();
        } else
            alert("Seleccione un item antes de cargar, o complete todos los datos");

        buscador_items_modelo = {};
    }





   
   
</script>
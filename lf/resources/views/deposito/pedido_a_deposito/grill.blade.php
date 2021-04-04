<div id="buscador_de_items" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Modal body text goes here.</p>
            </div>

        </div>
    </div>
</div>


<div class="row bg-dark ">

    <div class="col-12 col-md-8  mb-1">
        <div style="display: grid;  grid-template-columns: 10% 5% 15%  70%;">


            <label style="grid-column-start: 1;   font-size: 20px;  ">ITEM:</label>
            <a style="grid-column-start: 2; " href="#" onclick="buscador_de_items()"><i class="fa fa-search"></i></a>
            <input style="grid-column-start: 3; font-weight: 600; color: black; background-color: #a8fb37 !important;" class="form-control" type="text" id="ITEM-ID" readonly>

            <input readonly style="grid-column-start: 4;" class="form-control" style="width: 100% !important; height: 40px;" autocomplete="off" type="text" id="ITEM" placeholder="Buscar por descripcion">

        </div>
    </div>

    <div class="col-12  col-md-4  mb-1">
        <div style="display: grid;  grid-template-columns: 25% 40% 35%;">
            <label style="grid-column-start: 1;">CANTIDAD: </label>
            <input onkeydown="if(event.keyCode==13) {event.preventDefault(); cargar_tabla();}" style="grid-column-start: 2;" id="CANTIDAD" class="form-control decimal" type="text" />
            <label style="color: yellow;grid-column-start: 3;" id="MEDIDA"></label>
        </div>
    </div>




</div>

<div class="row bg-dark mt-2 pt-1 pb-2 pr-2 pl-2 pr-md-2 pl-md-2">
    <div class="col-12 col-md-12 p-0">
        <table class="table table-striped table-secondary text-dark">

            <thead>
                <tr style="font-family: mainfont;font-size: 18px;">
                    <th>DESCRIPCIÓN</th>
                    <th>UNI.MED.</th>
                    <th> CANTIDAD</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="PEDIDO-DETALLE">

            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">
                    </th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>



</div>




<script>
    var pedido_model = [];


    //ver

    async function buscador_de_items() {

        let req = await fetch("<?= url('buscador-items') ?>");
        let resp = await req.text();
        $("#buscador_de_items .modal-body").html(resp);
        await buscar_items__();
        $("#buscador_de_items").modal("show");
    }







    function deleteme(esto) {

        let row = $(esto.parentNode.parentNode);
        let id = row.attr("id");
        $(esto.parentNode.parentNode).remove();
        //quitar del modelo 
        pedido_model = pedido_model.filter(function(arg) {


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
        $("#PEDIDO-DETALLE").html("");
    }

    //actualizar tabla html
    function actualizar_fila(objec) {
        let existe = pedido_model.map((ar) => ar.ITEM).filter((ar) => parseInt(ar) == parseInt(objec.ITEM)).length;
        if (existe > 0) {
            let numero_de_filas = $("#PEDIDO-DETALLE tr").length;
            if (numero_de_filas == 0) return false;

            for (let nf = 0; nf < numero_de_filas; nf++) {
                let lafila = $("#PEDIDO-DETALLE tr")[nf];

                let esDeTipo = $(lafila).hasClass(objec.TIPO + "-class");


                if (String(lafila.id) == String(objec.ITEM) && esDeTipo) {
                    $(lafila).remove();
                    break;
                }
            }
        }
    }

    function actualiza_modelo_de_datos(NuevoObjeto) {

        let EXISTE = pedido_model.filter(
            (ar) => {
                return (ar.TIPO == NuevoObjeto.TIPO && ar.ITEM == String(NuevoObjeto.ITEM));
            }
        ).length;
        if (EXISTE == 0) {
            pedido_model.push(NuevoObjeto);
            return;
        }

        pedido_model = pedido_model.map(
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
        let descri = $("#ITEM").val();
        let cantidad = $("#CANTIDAD").val();
        let medida = $("#MEDIDA").text();


        /**Lista de elementos coincidentes en el modelo */
        let modeloDeItemSeleccionado = pedido_model.filter(
            (ar) => { 
                return (ar.TIPO == buscador_items_modelo.TIPO && ar.ITEM == String(buscador_items_modelo.REGNRO));
            }
        ); 
        if (regnro != "") {

            let des = "<td> " + descri + "</td>";
            let med = "<td> " + medida + "</td>";
            let cant = "<td>" + cantidad + "</td>";
            let del = "<td> <a style='color:black;' href='#' onclick='deleteme( this )'> <i class='fa fa-trash'></i> </a>  </td>";

            //agregar al modelo
            let TipoStock = buscador_items_modelo.TIPO  ;
            let objc = {
                ITEM: String(regnro),
                CANTIDAD: formValidator.limpiarNumero(cantidad),
                TIPO: TipoStock
            };

            let classIdentTipoItem = TipoStock +"-class";

            let nueva_tr = "<tr  class='" + classIdentTipoItem + "' id='" + regnro + "' >" + des + med + cant + del + "</tr>";

            //Actualizar modelo de datos
            actualiza_modelo_de_datos(objc);

            actualizar_fila(objc); //Quitar de la tabla para actualizar la fila


            $("#PEDIDO-DETALLE").append(nueva_tr);
            limpiar_campos_detalle();
        } else
            alert("Seleccione un item antes de cargar, o complete todos los datos");

        //buscador_items_modelo = {};
    }




  
</script>
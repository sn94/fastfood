<style>
    #MEDIDA {
        font-weight: 600;
        letter-spacing: 1px;
        background-color: var(--color-3);
        position: relative;
        z-index: 100000;
        top: -100%;
        color: white;
        border-radius: 8px 8px 0px 0px;
        text-align: center;
    }




    table thead tr th,
    table tbody tr td {
        padding: 0px !important;
        font-weight: 600;
    }
</style>
<div class="row" id="RESIDUOS-FIELDS">

    <div class="col-12 col-md-8  mb-1  d-flex flex-row">

        <label class="fs-6">Ítem:</label>
        <a href="#" onclick="buscarResiduo()"><i class="fa fa-search"></i></a>
        <input size="5" style="width:50px !important; font-weight: 600; color: black; " class="form-control ITEM-ID fs-6" type="text" id="ITEM-ID" disabled>

        <input disabled class="form-control ITEM fs-6" style="width: 100% !important; height: 40px;" autocomplete="off" type="text" id="ITEM">

    </div>

    <div class="col-12  col-md-4  mb-1 ps-0  d-flex flex-row">

        <label class="fs-6">Cantidad:&nbsp; </label>
        <div class="d-flex flex-column">
            <input onkeydown="if(event.keyCode==13) {event.preventDefault(); cargar_tabla();}" style="width: 90px !important;" id="CANTIDAD" class="form-control CANTIDAD decimal fs-6" type="text" />
            <label class="MEDIDA" id="MEDIDA"></label>
        </div>
        <a href="#" onclick="cargar_tabla()"><i class="fa fa-download"></i></a>

    </div>




</div>

<div class="row mt-2    ">
    <div class="col-12 col-md-12">
        <table class="table table-striped table-secondary text-dark">

            <thead>
                <tr>
                    <th>Cód.</th>
                    <th>Descripción</th>
                    <th>Medida</th>
                    <th> Cantidad</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="RESIDUOS-DETALLE">

                @if( isset($NOTA_RESIDUO_DETALLE) )

                @foreach( $NOTA_RESIDUO_DETALLE as $nr_d)
                <tr id="{{$nr_d->ITEM}}">
                    <td>
                        {{$nr_d->ITEM}}
                    </td>
                    <td>
                        {{$nr_d->stock->DESCRIPCION}}
                    </td>
                    <td>
                        {{$nr_d->MEDIDA}}
                    </td>
                    <td>
                        {{$nr_d->CANTIDAD}}
                    </td>
                    <td> <a style='color:black;' href='#' onclick='deleteme( this )'> <i class='fa fa-trash'></i> </a>  </td>
                </tr>
                @endforeach

                @endif

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
        let TIPOITEM = "MP";

        Buscador.url = "<?= url("stock/buscar") ?>/" + TIPOITEM;
        Buscador.htmlFormForParams = `<form> <input name='TIPO' type='hidden' value='${TIPOITEM}'> </form>`;

        Buscador.htmlFormFieldNames = ['TIPO'];
        Buscador.columnNames = ["REGNRO", "DESCRIPCION"];
        Buscador.columnLabels = ['ID', 'DESCRIPCION'];
        Buscador.callback = function(respuesta) {

            const {
                REGNRO,
                DESCRIPCION,
                unidad_medida
            } = respuesta;

            window.buscador_items_modelo = respuesta;

            $(PANEL_ID + " .ITEM-ID").val(REGNRO);
            $(PANEL_ID + " .ITEM").val(DESCRIPCION);
            $(PANEL_ID + " .MEDIDA").text(unidad_medida.UNIDAD);



        };
        Buscador.render();


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

            let codite = "<td> " + regnro + "</td>";
            let des = "<td> " + descri + "</td>";
            let med = "<td> " + medida + "</td>";
            let cant = "<td>" + cantidad + "</td>";
            let del = "<td> <a style='color:black;' href='#' onclick='deleteme( this )'> <i class='fa fa-trash'></i> </a>  </td>";

            //agregar al modelo
            let tipoStock = buscador_items_modelo.TIPO;
            let objc = { 
                ITEM: String(regnro),
                CANTIDAD: formValidator.limpiarNumero(cantidad),
                TIPO: tipoStock,
                MEDIDA: medida
            };

            let classIdentTipoItem = tipoStock + "-class";

            let nueva_tr = "<tr  class='" + classIdentTipoItem + "' id='" + regnro + "' >" + codite + des + med + cant + del + "</tr>";

            //Actualizar modelo de datos
            actualiza_modelo_de_datos(objc);

            actualizar_fila(objc); //Quitar de la tabla para actualizar la fila


            $("#RESIDUOS-DETALLE").append(nueva_tr);
            limpiar_campos_detalle();
        } else
            alert("Seleccione un item antes de cargar, o complete todos los datos");

        buscador_items_modelo = {};
    }




    
    async function restaurar_modelo_residuos() {
        //item cantidad tipo medida
        let row = document.querySelectorAll("#RESIDUOS-DETALLE tr");
        if (row.length == 0) return;

        let modelo = Array.prototype.map.call(row, function(domtr) {

 
            let ITEM = domtr.children[0].textContent.trim();
            let DESCRIPCION = domtr.children[1].textContent.trim();
            let MEDIDA = domtr.children[2].textContent.trim();
            let CANTIDAD = domtr.children[3].textContent.trim();
             
            return { 
                ITEM: ITEM,
                MEDIDA: MEDIDA,
                CANTIDAD: CANTIDAD
            };
        });
        residuos_model = modelo;
    }
</script>
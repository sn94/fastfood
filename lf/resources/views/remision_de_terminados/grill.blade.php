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
        <div style="display: flex; flex-direction: row;">


            <label style="  font-size: 20px;  ">ITEM:</label>
            @if( ! isset($PRODUCCION_DETALLE) )
            <a href="#" onclick="buscador_de_items()"><i class="fa fa-search"></i></a>
            @endif 

            <input size="5" style="width:50px !important; font-weight: 600; color: black; " class="form-control" type="text" id="ITEM-ID" disabled>

            <input disabled class="form-control" style="width: 100% !important; height: 40px;" autocomplete="off" type="text" id="ITEM">

        </div>
    </div>

    <div class="col-12  col-md-4  mb-1 pl-0 d-flex flex-row"  >

        <label  >CANTIDAD:&nbsp; </label>
        <div style="display: flex; flex-direction: column;">
            <input onkeydown="if(event.keyCode==13) {event.preventDefault(); cargar_tabla();}" style="grid-column-start: 2;width: 90px !important;" id="CANTIDAD" class="form-control decimal" type="text" />
            <label style="letter-spacing: 2px; font-weight: 600; color: yellow;grid-column-start: 3;" id="MEDIDA"></label>
        </div>

    </div>




</div>

<div class="container bg-dark mt-2 p-0  "> 
        <table class="table table-striped table-secondary text-dark">

            <thead>
                <tr style="font-family: mainfont;font-size: 18px;">
                    <th>CÓDIGO</th>
                    <th>DESCRIPCIÓN</th>
                    <th>UNI.MED.</th>
                    <th> CANTIDAD</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="REMISION-DETALLE">

                @if( isset($PRODUCCION_DETALLE) )
                @foreach( $PRODUCCION_DETALLE as $ITEM)

                @if( $ITEM->TIPO == "PE")
                <tr id="{{$ITEM->ITEM}}" class="{{$ITEM->TIPO}}-class">

                    <td>{{$ITEM->stock->CODIGO}}</td>
                    <td>{{$ITEM->stock->DESCRIPCION}}</td>
                    <td>{{$ITEM->MEDIDA}}</td>
                    <td>{{$ITEM->CANTIDAD}}</td>
                    <td> <a style='color:black;' href='#' onclick='editar_fila( this )'> <i class='fa fa-edit'></i> </a> </td>
                    <td> <a style='color:black;' href='#' onclick='deleteme( this )'> <i class='fa fa-trash'></i> </a> </td>
                </tr>
                @endif
                @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5">
                    </th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
</div>




<script>
    var remision_model = [];


    //ver

    async function buscador_de_items() {

        let req = await fetch("<?= url('buscador-items') ?>/PE");
        let resp = await req.text();
        $("#buscador_de_items .modal-body").html(resp);
        await buscar_items__();
        $("#buscador_de_items").modal("show");
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




    function editar_fila(esto) {


        let valor = esto.value;
        let rowid = esto.parentNode.parentNode.id;
        let coditem = esto.parentNode.parentNode.children[0].textContent
        let descr = esto.parentNode.parentNode.children[1].textContent;
        let canti = esto.parentNode.parentNode.children[3].textContent;
        let medi = esto.parentNode.parentNode.children[2].textContent;

        let tipo_stock = esto.parentNode.parentNode.className.split("-")[0];

        let regnro = $("#ITEM-ID").val(rowid);

        let descri = $("#ITEM").val(descr);
        let cantidad = $("#CANTIDAD").val(canti);
        let medida = $("#MEDIDA").text(medi);

        window.buscador_items_modelo = {
            CODIGO: coditem,
            ITEM: String(rowid),
            CANTIDAD: formValidator.limpiarNumero(canti),
            TIPO: tipo_stock,
            MEDIDA: medi
        };

    }



    function deleteme(esto) {

        let row = $(esto.parentNode.parentNode);
        let id = row.attr("id");
        $(esto.parentNode.parentNode).remove();
        //quitar del modelo 
        remision_model = remision_model.filter(function(arg) {


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
        $("#REMISION-DETALLE").html("");
    }

    //actualizar tabla html
    function actualizar_fila(objec) {
        let existe = remision_model.map((ar) => ar.ITEM).filter((ar) => parseInt(ar) == parseInt(objec.ITEM)).length;
        if (existe > 0) {
            let numero_de_filas = $("#REMISION-DETALLE tr").length;
            if (numero_de_filas == 0) return false;

            for (let nf = 0; nf < numero_de_filas; nf++) {
                let lafila = $("#REMISION-DETALLE tr")[nf];

                let esQueTipoStock = $(lafila).hasClass(objec.TIPO + "-class");

                if (String(lafila.id) == String(objec.ITEM) && esQueTipoStock) {
                    $(lafila).remove();
                    break;
                }
            }
        }
    }

    function actualiza_modelo_de_datos(NuevoObjeto) {

        let EXISTE = remision_model.filter(
            (ar) => {
                return (ar.TIPO == NuevoObjeto.TIPO && ar.ITEM == String(NuevoObjeto.ITEM));

            }
        ).length;
        if (EXISTE == 0) {
            remision_model.push(NuevoObjeto);
            return;
        }

        remision_model = remision_model.map(
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
        let coditem = buscador_items_modelo.CODIGO;
        let descri = $("#ITEM").val();
        let cantidad = $("#CANTIDAD").val();
        let medida = $("#MEDIDA").text();


        /**Lista de elementos coincidentes en el modelo */
        let modeloDeItemSeleccionado = remision_model.filter(
            (ar) => {
                return (ar.TIPO == buscador_items_modelo.TIPO && ar.ITEM == String(buscador_items_modelo.REGNRO));
            }
        );
        //let canti = modeloDeItemSeleccionado.length > 0 ? modeloDeItemSeleccionado[0].CANTIDAD : 1;

        if (regnro != "") {

            let cod_item = "<td> " + coditem + "</td>";
            let des = "<td> " + descri + "</td>";
            let med = "<td> " + medida + "</td>";
            let cant = "<td>" + cantidad + "</td>";
            let edit= "  <td> <a style='color:black;' href='#' onclick='editar_fila( this )'> <i class='fa fa-edit'></i> </a> </td>";
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

            let nueva_tr = "<tr  class='" + classIdentTipoItem + "' id='" + regnro + "' >" + cod_item + des + med + cant + edit+ del + "</tr>";

            //Actualizar modelo de datos
            actualiza_modelo_de_datos(objc);

            actualizar_fila(objc); //Quitar de la tabla para actualizar la fila


            $("#REMISION-DETALLE").append(nueva_tr);
            limpiar_campos_detalle();
        } else
            alert("Seleccione un item antes de cargar, o complete todos los datos");

        buscador_items_modelo = {};
    }
</script>
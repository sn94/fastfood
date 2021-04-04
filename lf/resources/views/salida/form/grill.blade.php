 



<style>
    #SALIDA-TABLE thead tr,
    #SALIDA-TABLE tbody tr,
    #SALIDA-TABLE tfoot tr {
        display: grid;
        grid-template-columns:  15% 45% 15% 15% 10%;
    }
</style>


@include("salida.form.grill_header")

<div class="row bg-dark  pt-1 pb-2 pr-2 pl-2 pr-md-2 pl-md-2">
    <div class="col-12 col-md-12 p-0">
        @if( isset($PRODUCCION_DETALLE) )
        Productos, materia prima y otros; solicitados en la ficha de producción
        @endif
        <table id="SALIDA-TABLE" class="table table-striped table-secondary text-dark">

            <thead>
                <tr>
                    <th>CÓDIGO</th>
                    <th>DESCRIPCIÓN</th>
                    <th>UNI.MED.</th>
                    <th> CANTIDAD</th>
                    
                    <th></th>
                </tr>
            </thead>
            <tbody id="SALIDA-DETALLE">

                @if( isset($PRODUCCION_DETALLE) )
                @foreach( $PRODUCCION_DETALLE as $ITEM)

                @if( $ITEM->TIPO != "PE")
                <tr id="{{$ITEM->ITEM}}" class="{{$ITEM->TIPO}}-class">


                    <td>{{$ITEM->stock->CODIGO}}</td>
                    <td>{{$ITEM->stock->DESCRIPCION}}</td>
                    <td>{{$ITEM->MEDIDA}}</td>
                    <td> {{$ITEM->CANTIDAD}}</td>
                    <td> <a class="mr-1" style='color:black;' href='#' onclick='editar_fila( this )'> <i class='fa fa-edit'></i> </a> 
                    <a style='color:black;' href='#' onclick='deleteme( this )'> <i class='fa fa-trash'></i> </a> </td>
                </tr>
                @endif
                @endforeach
                @endif

            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>



</div>




<script>
    var salida_model = [];


    //ver

    async function buscarItemParaSalida() {
        window.buscar_items_target = function(  {
            REGNRO,
            DESCRIPCION,
            MEDIDA
        }  ) {

            $("#SALIDA-ITEM-ID").val(REGNRO);
            $("#SALIDA-ITEM-DESC").val(DESCRIPCION);
            $("#SALIDA-MEDIDA").text(MEDIDA);
        };

        if ("buscar_items__" in window) {

            await buscar_items__();
        } else {
            let req = await fetch("<?= url('buscador-items') ?>");
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
                $("#SALIDA-ITEM-ID").val(suggestion.value);
                mostrar_item(suggestion.value);
            }
        });

    }





    function editar_fila(esto) {


        let valor = esto.value;
        let rowid = esto.parentNode.parentNode.id;
        let coditem= esto.parentNode.parentNode.children[0].textContent
        let descr = esto.parentNode.parentNode.children[1].textContent;
        let canti = esto.parentNode.parentNode.children[3].textContent;
        let medi = esto.parentNode.parentNode.children[2].textContent;

        let tipo_stock = esto.parentNode.parentNode.className.split("-")[0];

        let regnro = $("#SALIDA-ITEM-ID").val(rowid);
     
        let descri = $("#SALIDA-ITEM-DESC").val(descr);
        let cantidad = $("#SALIDA-CANTIDAD").val(canti);
        let medida = $("#SALIDA-MEDIDA").text(medi);

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
        salida_model = salida_model.filter(function(arg) {


            return String(arg.ITEM) != String(id);
        });
    }


    function limpiar_campos_detalle() {
        $("#SALIDA-ITEM-ID").val("");
        $("#SALIDA-ITEM-DESC").val("");
        $("#SALIDA-CANTIDAD").val("");
        $("#SALIDA-MEDIDA").text("");
    }


    function limpiar_tabla() {
        $("#SALIDA-DETALLE").html("");
    }

    //actualizar tabla html
    function actualizar_fila(objec) {
        let existe = salida_model.map((ar) => ar.ITEM).filter((ar) => parseInt(ar) == parseInt(objec.ITEM)).length;
        if (existe > 0) {
            let numero_de_filas = $("#SALIDA-DETALLE tr").length;
            if (numero_de_filas == 0) return false;

            for (let nf = 0; nf < numero_de_filas; nf++) {
                let lafila = $("#SALIDA-DETALLE tr")[nf];

                let esQueTipoStock = $(lafila).hasClass(objec.TIPO + "-class");

                if (String(lafila.id) == String(objec.ITEM) && esQueTipoStock) {
                    $(lafila).remove();
                    break;
                }
            }
        }
    }

    function actualiza_modelo_de_datos(NuevoObjeto) {

        let EXISTE = salida_model.filter(
            (ar) => {
                return (ar.TIPO == NuevoObjeto.TIPO && ar.ITEM == String(NuevoObjeto.ITEM));

            }
        ).length;
        if (EXISTE == 0) {
            salida_model.push(NuevoObjeto);
            return;
        }

        salida_model = salida_model.map(
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

        let regnro = $("#SALIDA-ITEM-ID").val();
        let cod_item= buscador_items_modelo.CODIGO;
        let descri = $("#SALIDA-ITEM-DESC").val();
        let cantidad = $("#SALIDA-CANTIDAD").val();
        let medida = $("#SALIDA-MEDIDA").text();


        /**Lista de elementos coincidentes en el modelo */
        /*let modeloDeItemSeleccionado = salida_model.filter(
            (ar) => {
                return (ar.TIPO == buscador_items_modelo.TIPO && ar.ITEM == String(buscador_items_modelo.REGNRO));
            }
        );*/
        //let canti = modeloDeItemSeleccionado.length > 0 ? modeloDeItemSeleccionado[0].CANTIDAD : 1;

        if (regnro != "") {

            let coditem = "<td> " + cod_item + "</td>";
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

            let nueva_tr = "<tr  class='" + classIdentTipoItem + "' id='" + regnro + "' >" + coditem+ des + med + cant + del + "</tr>";

            //Actualizar modelo de datos
            actualiza_modelo_de_datos(objc);

            actualizar_fila(objc); //Quitar de la tabla para actualizar la fila


            $("#SALIDA-DETALLE").append(nueva_tr);
            limpiar_campos_detalle();
        } else
            alert("Seleccione un item antes de cargar, o complete todos los datos");

        if ("buscador_items_modelo" in window)
            buscador_items_modelo = {};
    }





    var mostrar_item = async function(id) {
        let item = await get_item(id);
        if (Object.keys(item).length > 0) {
            // let PRECIO=   item.PVENTA;
            $("#SALIDA-MEDIDA").text(item.MEDIDA);
            $("#IVA").val(item.TRIBUTO);

        }
    }

    var get_item = async function(id) {
        let url_ = $("#RESOURCE-URL-ITEM").val() + "/" + id;
        let req = await fetch(url_, {
            headers: {
                formato: "json"
            }
        });
        let resp = await req.json();
        if ("ok" in resp) {
            return resp.ok;
        } else return {};
    };
</script>
<?php

use App\Helpers\Utilidades;
?>

@include("buscador.Buscador")

<div class="row m-0  mt-1   ">

    <div class="col-12 col-md-8 col-lg-8 p-0 m-0">
        <div class="p-0 m-0" style="display:  flex; flex-direction: row;">


            <label style="  font-size: 20px;  ">ITEM:</label>
            <a href="#" onclick="buscarItemParaReceta()"><i class="fa fa-search"></i></a>
            <input style="width: 60px;font-weight: 600; color: black;border: 1px solid #555 !important;  " class="form-control" type="text" id="FORM-STOCK-ITEM-ID" disabled>

            <input disabled class="form-control" style="width: 100% !important;border: 1px solid #555 !important;" autocomplete="off" type="text" id="FORM-STOCK-ITEM-DESC">

        </div>
    </div>

    <div class="col-12  col-sm-6 col-md-4 col-lg-4  p-0 m-0">
        <div style="display: flex; flex-direction: row;">
            <label>CANTIDAD: </label>
            <div style="display: flex; flex-direction: column;">
                <input oninput="formatoNumerico.formatearDecimal(event)" onkeydown="if(event.keyCode==13) {event.preventDefault(); stockModel.cargar_tabla();}" id="FORM-STOCK-CANTIDAD" class="form-control decimal" type="text" />
                <label style="color: black;font-size: 12px; font-weight: 600;" id="FORM-STOCK-MEDIDA"></label>
            </div>
            <a href="#" onclick="stockModel.cargar_tabla()"><i class="fa fa-download"></i></a>
        </div>
    </div>




</div>

<div class="row  p-0  m-0 ">
    <div class="col-12 col-md-12 ">
        <table id="STOCKRECETA" class="table table-striped table-secondary text-dark">

            <thead>
                <tr>
                    <th class="text-center">CÓDIGO</th>
                    <th>DESCRIPCIÓN</th>
                    <th>UNI.MED.</th>
                    <th> CANTIDAD</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="RECETA-DETALLE">
         
                @if( isset($RECETA))

                @foreach( $RECETA as $receta_)


                <tr id="{{$receta_->STOCK_ID}}" class="MP-class">
                    <td class="text-center">
                        {{$receta_->CODIGO != "" ?  $receta_->CODIGO  : ($receta_->BARCODE !="" ? $receta_->BARCODE :  $receta_->REGNRO)  }}
                    </td>
                    <td>
                        <input type="hidden" name="MPRIMA_ID[]" value="{{$receta_->MPRIMA_ID}}">
                        {{$receta_->materia_prima->DESCRIPCION}}
                    </td>
                    <td><input type='hidden' name='MEDIDA_[]' value="{{$receta_->MEDIDA_}}"> {{$receta_->MEDIDA_}}</td>
                    <td> <input type='hidden' name='CANTIDAD[]' value="{{$receta_->CANTIDAD}}"> {{ Utilidades::number_f($receta_->CANTIDAD) }}</td>
                    <td> <a style='color:black;' href='#' onclick='stockModel.deleteme( this )'> <i class='fa fa-trash'></i> </a> </td>
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
    var receta_model = [];



    var stockModel = {

        limpiar_campos_detalle: function() {
            $("#FORM-STOCK-ITEM-ID").val("");
            $("#FORM-STOCK-ITEM-DESC").val("");
            $("#FORM-STOCK-CANTIDAD").val("");
            $("#FORM-STOCK-MEDIDA").text("");
        },
        limpiar_tabla: function() {
            $("#RECETA-DETALLE").html("");
        },

        deleteme: function(esto) {

            let row = $(esto.parentNode.parentNode);
            let id = row.attr("id");
            $(esto.parentNode.parentNode).remove();
            //quitar del modelo 
            receta_model = receta_model.filter(function(arg) {
                return String(arg.ITEM) != String(id);
            });
        },
        actualizar_fila: function(objec) {
            let existe = receta_model.map((ar) => ar.ITEM).filter((ar) => parseInt(ar) == parseInt(objec.ITEM)).length;
            if (existe > 0) {
                let numero_de_filas = $("#RECETA-DETALLE tr").length;
                if (numero_de_filas == 0) return false;

                for (let nf = 0; nf < numero_de_filas; nf++) {
                    let lafila = $("#RECETA-DETALLE tr")[nf];

                    let esQueTipoStock = $(lafila).hasClass(objec.TIPO + "-class");

                    if (String(lafila.id) == String(objec.ITEM) && esQueTipoStock) {
                        $(lafila).remove();
                        break;
                    }
                }
            }
        },
        actualiza_modelo_de_datos: function(NuevoObjeto) {

            let EXISTE = receta_model.filter(
                (ar) => {
                    return (ar.TIPO == NuevoObjeto.TIPO && ar.ITEM == String(NuevoObjeto.ITEM));
                }
            ).length;
            if (EXISTE == 0) {
                receta_model.push(NuevoObjeto);
                return;
            }
            receta_model = receta_model.map(
                (ar) => {
                    if (ar.TIPO == NuevoObjeto.TIPO && ar.ITEM == String(NuevoObjeto.ITEM)) {
                        let obj_act = ar;
                        obj_act.CANTIDAD = parseFloat(NuevoObjeto.CANTIDAD);
                        return obj_act;
                    }
                    return ar;
                }
            );
        },
        cargar_tabla: function() {
            //if( Object.keys(buscador_items_modelo).length == 0  ) return;

            let codigo_item = buscador_items_modelo.CODIGO;
            let regnro = $("#FORM-STOCK-ITEM-ID").val();
            let descri = $("#FORM-STOCK-ITEM-DESC").val();
            let cantidad = $("#FORM-STOCK-CANTIDAD").val();
            let medida = $("#FORM-STOCK-MEDIDA").text();


            /**Lista de elementos coincidentes en el modelo */
            let modeloDeItemSeleccionado = receta_model.filter(
                (ar) => {
                    return (ar.TIPO == buscador_items_modelo.TIPO && ar.ITEM == String(buscador_items_modelo.REGNRO));
                }
            );
            //let canti = modeloDeItemSeleccionado.length > 0 ? modeloDeItemSeleccionado[0].CANTIDAD : 1;

            if (regnro == "" || cantidad == "") {
                alert("Seleccione un item antes de cargar, o complete todos los datos");
                return;
            }

            let coditem = "<td>" + codigo_item + "</td>";
            let des = "<td><input type='hidden' name='MPRIMA_ID[]' value='" + regnro + "'>" + descri + "</td>";
            let med = "<td><input type='hidden' name='MEDIDA_[]' value='" + medida + "'> " + medida + "</td>";
            let cant = "<td><input type='hidden' name='CANTIDAD[]' value='" + cantidad + "'>" + cantidad + "</td>";
            let del = "<td> <a style='color:black;' href='#' onclick='stockModel.deleteme( this )'> <i class='fa fa-trash'></i> </a>  </td>";

            //agregar al modelo
            let tipoStock = buscador_items_modelo.TIPO;
            let objc = {
                CODIGO: codigo_item,
                ITEM: String(regnro),
                CANTIDAD: formValidator.limpiarNumero(cantidad),
                TIPO: tipoStock
            };

            let classIdentTipoItem = tipoStock + "-class";

            let nueva_tr = "<tr  class='" + classIdentTipoItem + "' id='" + regnro + "' >" + coditem + des + med + cant + del + "</tr>";

            //Actualizar modelo de datos
            this.actualiza_modelo_de_datos(objc);

            this.actualizar_fila(objc); //Quitar de la tabla para actualizar la fila


            $("#RECETA-DETALLE").append(nueva_tr);
            this.limpiar_campos_detalle();



            buscador_items_modelo = {};
        },
        restaurar_modelo_receta: async function() {
            //item cantidad tipo medida
            let row = document.querySelectorAll("#RECETA-DETALLE tr");
            if (row.length == 0) return;

            let modelo = Array.prototype.map.call(row, function(domtr) {

                let ITEM = domtr.id;
                let CANTIDAD = domtr.children[3].textContent;
                let MEDIDA = domtr.children[2].textContent;
                let TIPO = domtr.className.split("-")[0];
                let CODIGO = domtr.children[0].textContent;
                return {
                    CODIGO: CODIGO,
                    ITEM: ITEM,
                    CANTIDAD: CANTIDAD,
                    MEDIDA: MEDIDA,
                    TIPO: TIPO
                };
            });
            receta_model = modelo;
        }

    }


    //ver

    async function buscarItemParaReceta() {
         //**** */
//Parametros de formulario
let tipos_de_item= { "MP" : "MATERIA PRIMA", "PP": "PARA VENTA", "PE":"PRODUCTO ELABORADO" , "AF": "MOBILIARIO Y OTROS"};
        let htmlParams= Object.entries(tipos_de_item).map( ([key, val])=>{
            return `<option value='${key}'>${val}</option>`;
        });
        htmlParams= `<form><input type='hidden' value='MP' name='tipo' /></form>`;

        Buscador.url = "<?= url("stock/buscar") ?>";
        Buscador.httpMethod= "post";
        Buscador.httpHeaders= { formato: "json" };
        Buscador.columnNames = ["REGNRO", "DESCRIPCION" ];
        Buscador.columnLabels = ['ID', 'DESCRIPCIÓN'];
        Buscador.htmlFormForParams=  htmlParams; 
        Buscador.htmlFormFieldNames= ['tipo'];


        Buscador.callback = function(seleccionado) {

            window.buscador_items_modelo= seleccionado;
            $('#FORM-STOCK-ITEM-ID').val(seleccionado.REGNRO);
            $('#FORM-STOCK-ITEM-DESC').val(seleccionado.DESCRIPCION);
            $("#FORM-STOCK-MEDIDA").text( seleccionado.unidad_medida.DESCRIPCION);
           
        };
        Buscador.render();
     
    }










    //actualizar tabla html
</script>
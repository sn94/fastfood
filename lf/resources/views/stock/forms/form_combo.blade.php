<?php

use App\Helpers\Utilidades;
?>



<div class="row m-0  mt-1 g-0  " style="background-color: var(--color-3);">


    <div class="col-12">
        <p style="background-color: var(--color-3);
font-weight: bold;
text-align: center; 
letter-spacing: 3px;
border-bottom: 1px solid var(--color-1);">Combo</p>
    </div>
    <div class="col-12 col-md-8 col-lg-8 p-0 m-0">

        <div class="p-0 m-0  d-flex flex-row">

            <label style="  font-size: 20px;  ">ITEM:</label>
            <a href="#" onclick="buscarProductoParaCombo()"><i class="fa fa-search"></i></a>

            <input style="width: 60px;font-weight: 600; color: black;border: 1px solid #555 !important;  " class="form-control" type="text" id="FORM-COMBO-ITEM-ID" disabled>

            <input disabled class="form-control" style="width: 100% !important;border: 1px solid #555 !important;" autocomplete="off" type="text" id="FORM-COMBO-ITEM-DESC">

        </div>
    </div>

    <div class="col-12  col-sm-6 col-md-4 col-lg-4  p-0 m-0 d-flex flex-row">
        <label>CANTIDAD: </label>
        <div style="display: flex; flex-direction: column;">
            <input oninput="formatoNumerico.formatearDecimal(event)" onkeydown="if(event.keyCode==13) {event.preventDefault(); tableDetalleComboModel.cargar_tabla();}" id="FORM-COMBO-CANTIDAD" class="form-control decimal" type="text" />
          
        </div>
        <a href="#" onclick="tableDetalleComboModel.cargar_tabla()"><i class="fa fa-download"></i></a>

    </div>




</div>

<div class="row  p-0 pt-1  m-0 ">

    <div class="col-12 col-md-12 p-0">
        <table id="STOCKRECETA" class="table table-striped table-secondary text-dark">

            <thead>
                <tr>
                    <th class="text-center">CÓDIGO</th>
                    <th>DESCRIPCIÓN</th>
                    
                    <th> CANTIDAD</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="COMBO-DETALLE">

                @if( isset( $COMBO) )

                @foreach( $COMBO as $combo_)
            

                <tr id="{{$combo_->STOCK_ID}}">
                    <td class="text-center">
                        {{ $combo_->STOCK_ID }}
                    </td>
                    <td>
                        <input type="hidden" name="COMBO_STOCK_ID[]" value="{{$combo_->STOCK_ID}}">
                        {{$combo_->stock->DESCRIPCION}}
                    </td>
                   
                    <td> <input type='hidden' name='COMBO_CANTIDAD[]' value="{{$combo_->CANTIDAD}}"> {{ Utilidades::number_f($combo_->CANTIDAD) }}</td>
                    <td> <a style='color:black;' href='#' onclick='tableDetalleComboModel.deleteme( this )'> <i class='fa fa-trash'></i> </a> </td>
                </tr>

                @endforeach

                @endif



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
    var combo_model = [];



    var tableDetalleComboModel = {

        limpiar_campos_detalle: function() {
            $("#FORM-COMBO-ITEM-ID").val("");
            $("#FORM-COMBO-ITEM-DESC").val("");
            $("#FORM-COMBO-CANTIDAD").val("");
           
        },
        limpiar_tabla: function() {
            $("#COMBO-DETALLE").html("");
        },

        deleteme: function(esto) {

            let row = $(esto.parentNode.parentNode);
            let id = row.attr("id");
            $(esto.parentNode.parentNode).remove();
            //quitar del modelo 
            combo_model = combo_model.filter(function(arg) {
                return String(arg.ITEM) != String(id);
            });
        },
        actualizar_fila: function(objec) {
            let existe = combo_model.map((ar) => ar.ITEM).filter((ar) => parseInt(ar) == parseInt(objec.ITEM)).length;
            if (existe > 0) {
                let numero_de_filas = $("#COMBO-DETALLE tr").length;
                if (numero_de_filas == 0) return false;

                for (let nf = 0; nf < numero_de_filas; nf++) {
                    let lafila = $("#COMBO-DETALLE tr")[nf];

                    let esQueTipoStock = $(lafila).hasClass(objec.TIPO + "-class");

                    if (String(lafila.id) == String(objec.ITEM) && esQueTipoStock) {
                        $(lafila).remove();
                        break;
                    }
                }
            }
        },
        actualiza_modelo_de_datos: function(NuevoObjeto) {

            let EXISTE = combo_model.filter(
                (ar) => {
                    return (ar.TIPO == NuevoObjeto.TIPO && ar.ITEM == String(NuevoObjeto.ITEM));
                }
            ).length;
            if (EXISTE == 0) {
                combo_model.push(NuevoObjeto);
                return;
            }
            combo_model = combo_model.map(
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
            let regnro = $("#FORM-COMBO-ITEM-ID").val();
            let descri = $("#FORM-COMBO-ITEM-DESC").val();
            let cantidad = $("#FORM-COMBO-CANTIDAD").val();
             


            /**Lista de elementos coincidentes en el modelo */
            let modeloDeItemSeleccionado = combo_model.filter(
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
            let des = "<td><input type='hidden' name='COMBO_STOCK_ID[]' value='" + regnro + "'>" + descri + "</td>";
          
            let cant = "<td><input type='hidden' name='COMBO_CANTIDAD[]' value='" + cantidad + "'>" + cantidad + "</td>";
            let del = "<td> <a style='color:black;' href='#' onclick='tableDetalleComboModel.deleteme( this )'> <i class='fa fa-trash'></i> </a>  </td>";

            //agregar al modelo
            let tipoStock = buscador_items_modelo.TIPO;
            let objc = {
                CODIGO: codigo_item,
                ITEM: String(regnro),
                CANTIDAD: formValidator.limpiarNumero(cantidad),
                TIPO: tipoStock
            };

            let classIdentTipoItem = tipoStock + "-class";

            let nueva_tr = "<tr  class='" + classIdentTipoItem + "' id='" + regnro + "' >" + coditem + des  + cant + del + "</tr>";

            //Actualizar modelo de datos
            this.actualiza_modelo_de_datos(objc);

            this.actualizar_fila(objc); //Quitar de la tabla para actualizar la fila


            $("#COMBO-DETALLE").append(nueva_tr);
            this.limpiar_campos_detalle();



            buscador_items_modelo = {};
        },
        restaurar_modelo_combo: async function() {
            //item cantidad  
            let row = document.querySelectorAll("#COMBO-DETALLE tr");
            if (row.length == 0) return;

            let modelo = Array.prototype.map.call(row, function(domtr) {

                let ITEM = domtr.id;
                let CANTIDAD = domtr.children[2].textContent.trim();
               
               
                let TIPO = domtr.className.split("-")[0];
                let CODIGO = domtr.children[0].textContent.trim();
                return {
                    CODIGO: CODIGO,
                    ITEM: ITEM,
                    CANTIDAD: CANTIDAD, 
                    TIPO: TIPO
                };
            });
            combo_model = modelo;
        }

    }


    //ver

    async function buscarProductoParaCombo() {
        console.log("Combo");
        //**** */
        //Parametros de formulario
        let tipos_de_item = {
            "PP": "PARA VENTA",
            "PE": "PRODUCTO ELABORADO"
        };
        let htmlParams = Object.entries(tipos_de_item).map(([key, val]) => {
            return `<option  value='${key}'>${val}</option>`;
        });
        htmlParams = `
        <form><select style='border:none; border-bottom: 1px solid black;' onchange=' Buscador.filtrar(this)'   >${htmlParams}</select>
          <input type='hidden' value='VENTA' name='tipo'  /> </form>`;

        Buscador.url = "<?= url("stock/buscar") ?>";
        Buscador.httpMethod = "post";
        Buscador.httpHeaders = {
            formato: "json"
        };
        Buscador.columnNames = ["REGNRO", "DESCRIPCION"];
        Buscador.columnLabels = ['ID', 'DESCRIPCIÓN'];
        Buscador.htmlFormForParams = htmlParams;
        Buscador.htmlFormFieldNames = ['tipo'];


        Buscador.callback = function(seleccionado) {

            window.buscador_items_modelo = seleccionado;
            $('#FORM-COMBO-ITEM-ID').val(seleccionado.REGNRO);
            $('#FORM-COMBO-ITEM-DESC').val(seleccionado.DESCRIPCION);
     

        };
        Buscador.render();

    }










    //actualizar tabla html
</script>
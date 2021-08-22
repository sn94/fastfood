<script>
    function deepClone(obj) {

        var clone = {};

        //Esta vacio?
        //if( Object.keys(  obj).length  == 0  )
        //clone[]
        for (var key_ in obj) {


            var valor = obj[key_];
            if (typeof valor != "object")
                clone[key_] = valor;
            else {
                if (Array.isArray(valor))
                    clone[key_] = valor.slice();
                else
                    clone[key_] = deepClone(valor);
            }
        }
        return clone;
    }




    var itemSearcherObject = {


        name: "",
        panel_name: "",
        modal_name: "",
        data_model: [],
        medidas: ["UNIDAD", "KILO", "GRAMO", "PAQUETE"],
        callbackPostModal:undefined,

        // buscador_items_modelo: {},
        buscador_de_items: async function(TIPOITEM) {

            /*  Backup

            let tipo = TIPOITEM == undefined ? "" : "/" + TIPOITEM;
            let req = await fetch("<?= url('buscador-items') ?>" + tipo);
            let resp = await req.text();
            $(this.modal_name + " .modal-body").html(resp);
            await window.buscar_items__();
            $(this.modal_name).modal("show");*/

            Buscador.url = "<?= url("stock/buscar") ?>/"+TIPOITEM;
        Buscador.htmlFormForParams = `<form> <input name='TIPO' type='hidden' value='${TIPOITEM}'> </form>`;
        Buscador.htmlFormFieldNames = ['TIPO'];
        Buscador.columnNames = ["REGNRO", "DESCRIPCION"];
        Buscador.columnLabels = ['ID', 'DESCRIPCION'];
        Buscador.callback =  this.callbackPostModal;
        Buscador.render();


        },
        actualiza_modelo_de_datos: function(NuevoObjeto) {


            let EXISTE = this.data_model.filter(
                (ar) => {
                    return (ar.ITEM == String(NuevoObjeto.ITEM));
                }
            ).length;


            if (EXISTE == 0) {
           
                this.data_model.push(NuevoObjeto);
                return;
            }
            this.data_model = this.data_model.map(
                (ar) => {

                    if (ar.ITEM == String(NuevoObjeto.ITEM)) {
                        let obj_act = ar;
                        obj_act.CANTIDAD = parseFloat(NuevoObjeto.CANTIDAD);
                        return obj_act;
                    }
                    return ar;
                }
            );
        },

        delete_row: function(esto, TIPO) {
            console.log(  TIPO);
            let row = $(esto.parentNode.parentNode);
            let id = row.attr("id");
            $(esto.parentNode.parentNode).remove();
            //quitar del modelo 

            let contexto=  undefined;
            switch( TIPO){
               case "PE" : contexto= productos_controller ;break;
               case "MP": contexto= ingredientes_controller;  break;
               case "AF": contexto= insumos_controller;  break;
            }
            contexto.data_model = contexto.data_model.filter(function(arg) {
                return String(arg.ITEM) != String(id);
            });
        },


        limpiar_campos_detalle: function() {
            $(this.panel_name + " .ITEM-ID").val("");
            $(this.panel_name + " .ITEM").val("");
            $(this.panel_name + " .CANTIDAD").val("");
            $(this.panel_name + " .MEDIDA").text("");
        },

        limpiar_tabla: function() {
            $(this.panel_name + ".DETALLE").html("");
        },
        actualizar_fila: function(objec) {
            let existe = this.data_model.map((ar) => ar.ITEM).filter((ar) => parseInt(ar) == parseInt(objec.ITEM)).length;
            if (existe > 0) {
                let numero_de_filas = $(this.panel_name + " .DETALLE tr").length;
                if (numero_de_filas == 0) return false;

                for (let nf = 0; nf < numero_de_filas; nf++) {
                    let lafila = $(this.panel_name + " .DETALLE tr")[nf];
                    if (String(lafila.id) == String(objec.ITEM)) {
                        $(lafila).remove();
                        break;
                    }
                }
            }
        },

        formar_tr_table({
            CODIGO,
            DESCRIPCION,
            MEDIDA,
            CANTIDAD,
            ITEM,
            TIPO
        }) {


            window.delete_row = this.delete_row;
            // window[  Symbol.for(  this.name )   ]=    this;
            window.temporalContext = this;
            let codite = "<td>" + CODIGO + "</td>";
            let des = "<td> " + DESCRIPCION + "</td>";
            let med = "<td> " + MEDIDA + "</td>";
            let cant = "<td>" + CANTIDAD + "</td>";
            let del = `
            <td> <a style='color:black;' href='#' onclick='delete_row( this, "${TIPO}" )'> <i class='fa fa-trash'></i> </a>  </td>
            `;

            let classIdentTipoItem = TIPO + "-class";
            let nueva_tr = "<tr  class='" + classIdentTipoItem + "' id='" + ITEM + "' >" + codite + des + med + cant + del + "</tr>";
            return nueva_tr;
        },


        cargar_tabla: function(objectData) {
  
            let regnro = "";
            let codigo_item = "";
            let descri = "";
            let cantidad = "";
            let medida = "";


            if (objectData == undefined) {
                regnro = $(this.panel_name + " .ITEM-ID").val();
                codigo_item = window.buscador_items_modelo.CODIGO;
                descri = $(this.panel_name + "  .ITEM").val();
                cantidad = $(this.panel_name + "  .CANTIDAD").val();
                medida = $(this.panel_name + "  .MEDIDA").text();
            } else {
                regnro = objectData.ITEM;
                codigo_item = objectData.stock.CODIGO;
                descri = objectData.stock.DESCRIPCION;
                cantidad = objectData.CANTIDAD;
                medida = objectData.MEDIDA;
            }
            if (regnro != "") {

                //agregar al modelo
                let tipoStock = window.buscador_items_modelo ? window.buscador_items_modelo.TIPO  : objectData.TIPO;
                let objc = {
                    CODIGO: codigo_item,
                    ITEM: String(regnro),
                    CANTIDAD: formValidator.limpiarNumero(cantidad),
                    DESCRIPCION: descri,
                    TIPO: tipoStock,
                    MEDIDA: medida
                };
       
                //Actualizar modelo de datos
                this.actualiza_modelo_de_datos(objc);
                this.actualizar_fila(objc); //Quitar de la tabla para actualizar la fila

                let nueva_tr = this.formar_tr_table(objc);
                $(this.panel_name + " .DETALLE").append(nueva_tr);
                this.limpiar_campos_detalle();
            } else alert("Seleccione un item antes de cargar, o complete todos los datos");
            window.buscador_items_modelo = {};
        },


        buscarEnStock: async function(TIPOSTOCK) {
            let PANEL_ID = this.panel_name;
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
                $("#BUSCADOR-TIPO-STOCK").val(TIPOSTOCK);
                await buscar_items__();
            } else {
                let req = await fetch("<?= url('buscador-items') ?>/" + TIPOSTOCK);
                let resp = await req.text();
                $("body").append(resp);
                await buscar_items__();
            }
        }

    };
</script>
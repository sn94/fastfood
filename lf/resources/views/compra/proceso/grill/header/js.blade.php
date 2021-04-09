<script>
    async function onCreateStock() {
        let url_ = "<?= url('stock/create') ?>";
        let req = await fetch(url_, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        let resp = await req.text();


        $("#mymodal .content").html(resp);

        window.myModalCustomHandler = function() {
            if ("ULTIMO_STOCK" in window && ULTIMO_STOCK != undefined) {
                $("#COMPRA-ITEM-ID").val(ULTIMO_STOCK.REGNRO);
                $("#COMPRA-ITEM-DESC").val(ULTIMO_STOCK.DESCRIPCION);
                window.buscador_items_modelo = Object.assign(ULTIMO_STOCK);
                delete window.ULTIMO_STOCK;
            }

        };

        //dar formato a campos decimales y numericos 
        formatoNumerico.formatearCamposNumericosDecimales("STOCKFORM");

        $("#mymodal").removeClass("d-none");
    }




    var compraObj = {

        /**Limpieza de tabla y campos de totales */
        limpiar_campos_detalle: function() {
            $("#COMPRA-ITEM-ID").val("");
            $("#COMPRA-ITEM-DESC").val("");
            $("#COMPRA-PRECIO").val("");
            $("#COMPRA-CANTIDAD").val("");
            $("#COMPRA-MEDIDA").text("");
        },


        limpiar_tabla: function() {
            $("#COMPRA-DETALLE").html("");
            //$("#TOTALEXE").text("0");
            $("#TOTAL5").text("0");
            $("#TOTAL10").text("0");
        },


        /** Actualizacion de modelo de tabla */
        actualizar_modelo: function(NuevoObjeto) {
            let existe = compra_model.filter(
                (ar) => {
                    return parseInt(ar.ITEM) == parseInt(NuevoObjeto.ITEM) && ar.TIPO == NuevoObjeto.TIPO;
                }
            ).length;
            if (existe == 0) {
                compra_model.push(NuevoObjeto);
                return;
            }
            compra_model = compra_model.map(
                (ar) => {
                    if (parseInt(ar.ITEM) == parseInt(NuevoObjeto.ITEM) && ar.TIPO == NuevoObjeto.TIPO) {
                        let newobj = {
                            ...NuevoObjeto
                        };
                        /*  let newobj= ar;
                          newobj.CANTIDAD = NuevoObjeto.CANTIDAD;
                           newobj.IVA5= NuevoObjeto.IVA5;
                           newobj.IVA10= NuevoObjeto.IVA10;*/

                        return newobj;
                    } else
                        return ar;
                }
            );


        },

        /**Actualizacion de vista */
        actualizar_tabla_html: function(NuevoObjeto) {

            let existe = compra_model.filter(
                (ar) => {
                    return parseInt(ar.ITEM) == parseInt(NuevoObjeto.ITEM) && ar.TIPO == NuevoObjeto.TIPO;
                }
            ).length;

            if (existe > 0) {
                let numero_de_filas = $("#COMPRA-DETALLE tr").length;
                if (numero_de_filas > 0) {
                    for (let nf = 0; nf < numero_de_filas; nf++) {
                        let lafila = $("#COMPRA-DETALLE tr")[nf];

                        let tipoDeItem = NuevoObjeto.TIPO + "-class";
                        let esTipoDeItem = $(lafila).hasClass(tipoDeItem); //LA FILA QUE COINCIDE ES UN PRODUCTO O MATERIAPRIMA

                        if (String(lafila.id) == String(NuevoObjeto.ITEM) && esTipoDeItem) {
                            $(lafila).remove();
                            break;
                        }
                    }
                }
            }

            //Preparar campos 
            let codigo = "<td> " + NuevoObjeto.CODIGO + "</td>";
            let des = "<td> " + NuevoObjeto.DESCRIPCION + "</td>";

            let med = "<td class='text-end'>     " + NuevoObjeto.MEDIDA + "</td>";
            let prec = "<td class='text-end' >    " + formatoNumerico.darFormatoEnMillares(NuevoObjeto.P_UNITARIO, 0) + "</td>";
            let cant = "<td class='text-end' >    " + formatoNumerico.darFormatoEnMillares(NuevoObjeto.CANTIDAD, 3) + "</td>";
            // let ex = "<td class='text-end' >    " + formatoNumerico.darFormatoEnMillares(exe, 0) + "</td>";
            let iv5 = "<td class='text-end' >    " + formatoNumerico.darFormatoEnMillares(NuevoObjeto.IVA5, 0) + "</td>";
            let iv10 = "<td class='text-end' >    " + formatoNumerico.darFormatoEnMillares(NuevoObjeto.IVA10, 0) + "</td>";
            let del = "<td style='width: 100px;display: flex;flex-direction: row; justify-content: center;'> <a class='mr-1 ml-1' style='color:black;' href='#' onclick='compraObj.onDeleteRowFromGrill( this )'> <i class='fa fa-trash'></i> </a>  </td>";

            let claseIdentiTipoItem = NuevoObjeto.TIPO + "-class";
            let nuevatr = `
            <tr class='${claseIdentiTipoItem}' id='${NuevoObjeto.ITEM}' >${codigo} ${des} ${med} ${prec}  ${cant} ${iv5} ${iv10} ${del} </tr>
            `;
            console.log("fila", nuevatr);
            $("#COMPRA-DETALLE").append(nuevatr);

        },


        /**Actualizacion de totales */
        calcular_totales: function() {

            if (compra_model.length == 0) {

                $("#TOTAL5").text("0");
                $("#TOTAL10").text("0");
                $("#TOTALFACTURA").text("0");
                return;
            }
            let totales2 = compra_model.map((ar) => ar.IVA5).reduce(function(suma, elemento) {

                return suma + parseInt(elemento);
            }, 0);

            let totales3 = compra_model.map((ar) => ar.IVA10).reduce(function(suma, elemento) {

                console.log(suma);
                return suma + parseInt(elemento);
            }, 0);

            $("#TOTAL5").text(formatoNumerico.darFormatoEnMillares(totales2, 0));
            $("#TOTAL10").text(formatoNumerico.darFormatoEnMillares(totales3, 0));
            let totalFactura = totales2 + totales3;
            if ($("#COMPRAS-TABLE tbody").children().length == 0)
                $("#TOTALFACTURA").text("0");
            else
                $("#TOTALFACTURA").text(formatoNumerico.darFormatoEnMillares(totalFactura, 0));
        },


        /**Insercion de fila en tabla */
        cargar_tabla: function() {

            let regnro = buscador_items_modelo.REGNRO;
            let descri = buscador_items_modelo.DESCRIPCION;
            let precio = parseInt(formValidator.limpiarNumero($("#COMPRA-PRECIO").val()));
            let canti = parseFloat(formValidator.limpiarNumero($("#COMPRA-CANTIDAD").val()));
            let medida = buscador_items_modelo.unidad_medida.DESCRIPCION;
            let iva = parseInt($("#COMPRA-IVA").val());
            let tipostock = buscador_items_modelo.TIPO;
            let subtotal = Math.round(parseFloat(precio) * (canti));

            let exe = iva == 0 ? subtotal : 0;
            let i5 = iva == 5 ? subtotal : 0;
            let i10 = iva == 10 ? subtotal : 0;

            if (regnro == "" || (precio == "" || precio == "0") || canti == "") {
                alert("Seleccione un item antes de cargar, o complete todos los datos");
                return;
            }
            //agregar al modelo
            let objc = {
                CODIGO: buscador_items_modelo.CODIGO,
                DESCRIPCION: descri,
                MEDIDA: medida,
                ITEM: regnro,
                CANTIDAD: canti,
                P_UNITARIO: precio,
                IVA5: i5,
                IVA10: i10,
                TIPO: tipostock
            };

            this.actualizar_modelo(objc);
            this.actualizar_tabla_html(objc);

            this.limpiar_campos_detalle();
            this.calcular_totales();



        },


        onDeleteRowFromGrill: function(esto) {


            let row = $(esto.parentNode.parentNode);
            let id = row.attr("id"); //ID STOCK

            $(esto.parentNode.parentNode).remove();
            //quitar del modelo 
            compra_model = compra_model.filter(function(arg) {

                //   let classIdenti = arg.TIPO + "-class";
                //  let filaBorrarContieneClase = row.hasClass(classIdenti);
                // console.log( arg,   String(arg.ITEM) != String(id) && !filaBorrarContieneClase );
                return String(arg.ITEM) != String(id);
            });
            this.calcular_totales();
        }
    };

    async function buscarItemParaCompra() {

        //Handler
        window.buscar_items_target = function({
            REGNRO,
            DESCRIPCION,
            unidad_medida,
            PCOSTO
        }) {

            $("#COMPRA-ITEM-ID").val(REGNRO);
            $("#COMPRA-ITEM-DESC").val(DESCRIPCION);
            $("#COMPRA-MEDIDA").text( unidad_medida.DESCRIPCION);
            $("#COMPRA-PRECIO").val(formatoNumerico.darFormatoEnMillares(PCOSTO, 0));
        }; /**End handler */

        if ("buscar_items__" in window) {

            await buscar_items__();
        } else {
            let req = await fetch("<?= url('buscador-items') ?>");
            let resp = await req.text();
            $("body").append(resp);
            await buscar_items__();
        }

    }



    /**Metodos para agregado, edicion y eliminacion de filas del detalle */
</script>
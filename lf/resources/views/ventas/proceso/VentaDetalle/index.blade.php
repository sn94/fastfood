<style>
    #grill-ticket tr td,
    #VENTATABLE thead tr th {
        padding: 0px !important;
    }

    button[id^=plusbtn],
    button[id^=minusbtn] {
        margin: 0px !important;
        display: inline;
    }
</style>



<div style="height: 100%;overflow-y: scroll;background-color:  var(--color-6) !important;">
    <table id="VENTATABLE" class="table table-stripped table-light">
        <thead class="thead-dark">
            <tr>
                <th>Cantidad</th>
                <th>Descripción</th>
                <th class="text-end">Precio</th>
                <!--  <th>Exe.</th>-->
                <th class="text-end">Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="grill-ticket" style="font-weight: 600; color: black;">

        </tbody>

    </table>
</div>


<script>
    var ventas_model = [];
    var PARAM_PRECIO_MULTIPLE = false;





    function calcular_total_modelo() {
        return ventas_model.map((ar) => {
            return parseInt(ar.CANTIDAD) * parseInt(ar.P_UNITARIO);
        }).reduce((previos, current) => previos + current, 0);
    }



    function editar_cantidad_item(Esto) {

        let btnType = $(Esto).hasClass("plusbtn") ? "plusbtn" : "minusbtn";

        //Obtener ID de fila
        let IDROW = Esto.parentNode.parentNode.id;

        let encontrado = ventas_model.filter((ar) =>
            ("row" + ar.ITEM + "-" + ar.ID_PRECIO + "-" + ar.TIPO_PRECIO) == IDROW);


        if (encontrado.length > 0) {
            if ((encontrado[0].CANTIDAD - 1) == 0 && btnType == "minusbtn") return;

            ventas_model = ventas_model.map(
                (ar) => {
                    if (("row" + ar.ITEM + "-" + ar.ID_PRECIO + "-" + ar.TIPO_PRECIO) == IDROW) {
                        let modificado = {
                            ...ar
                        };
                        if (btnType == "plusbtn")
                            modificado.CANTIDAD += 1;
                        else //O minusbtn
                            modificado.CANTIDAD -= 1;
                        return modificado;
                    }
                    return ar;
                }
            );
            //  Actualizar Html
            let nuevoItemModelo = ventas_model
                .filter((ar) => ("row" + ar.ITEM + "-" + ar.ID_PRECIO + "-" + ar.TIPO_PRECIO) == IDROW)[0];

            if (parseInt(nuevoItemModelo.CANTIDAD) > 0)
                actualizar_html_tabla(nuevoItemModelo);
        }
    }

    function eliminar_de_modelo(IdObj) { //ID name in DOM
        ventas_model = ventas_model.filter((ar) =>
            ("row" + ar.ITEM + "-" + ar.ID_PRECIO + "-" + ar.TIPO_PRECIO) != IdObj);
    }

    function actualizar_modelo(NuevoObj) {


        let coincidencias = ventas_model
            .filter((ar) => ar.ITEM == NuevoObj.ITEM && ar.TIPO_PRECIO == NuevoObj.TIPO_PRECIO && ar.ID_PRECIO == NuevoObj.ID_PRECIO).length;
        if (coincidencias == 0) {
            ventas_model.push(NuevoObj);
        } else {
            ventas_model = ventas_model.map((ar) => {
                if (ar.ITEM == NuevoObj.ITEM && ar.TIPO_PRECIO == NuevoObj.TIPO_PRECIO && ar.ID_PRECIO == NuevoObj.ID_PRECIO) {
                    ar.CANTIDAD = parseInt(ar.CANTIDAD) + 1;
                    let subtotal = parseInt(ar.CANTIDAD) * parseInt(ar.P_UNITARIO);

                    if (ar.IVA == "0") ar.EXENTA = subtotal;
                    if (ar.IVA == "10") ar.TOT10 = subtotal;
                    if (ar.IVA == "5") ar.TOT5 = subtotal;
                    return ar;
                } else return ar;
            })
        }
    }



    function actualizar_html_tabla(Obj) {

        let td_cons = function(ar) {
            return Object.keys(ar).map((valo) => {
                if (valo == "CANTIDAD")
                    return "<td style='display: flex;' class='text-left'>" + ar[valo] + "</td>";
                else {
                    if (valo == "DESCRIPCION")
                        return "<td class='text-left'>" + ar[valo] + "</td>";
                    else
                        return "<td class='text-end'>" + ar[valo] + "</td>";
                }
            }).join("");
        };

        //id stock idprecio modus
        let ROWID_A_BUSCAR = "row" + Obj.ITEM + "-" + Obj.ID_PRECIO + "-" + Obj.TIPO_PRECIO;

        let filas = document.querySelectorAll(" #grill-ticket tr ");
        let filtrado = Array.prototype.filter.call(filas, function({
            id
        }) {
            return (ROWID_A_BUSCAR == id);
        });

        let objTable = {
            ...Obj,
        };

        objTable.DEL_BUTTON = "<button class='btn btn-sm btn-danger' type='button' onclick='borrarFila(this)' >(-)</button>";
        objTable.CANTIDAD = `
    <button type='button'   onclick='editar_cantidad_item(this)' class='btn btn-sm btn-success plusbtn'>(+)</button>
    <button  type='button'  onclick='editar_cantidad_item(this)' class='btn btn-sm btn-danger minusbtn'>(-)</button>
    <p style='width: 100%;text-align:center; width: 40px !important;'> ${objTable.CANTIDAD}</p>
    
    `;


        objTable.SUBTOTAL = formatoNumerico.darFormatoEnMillares(parseInt(Obj.P_UNITARIO) * parseInt(Obj.CANTIDAD), 0);
        objTable.P_UNITARIO = formatoNumerico.darFormatoEnMillares(Obj.P_UNITARIO, 0);
        objTable.EXENTA = formatoNumerico.darFormatoEnMillares(Obj.EXENTA, 0);
        objTable.TOT5 = formatoNumerico.darFormatoEnMillares(Obj.TOT5, 0);
        objTable.TOT10 = formatoNumerico.darFormatoEnMillares(Obj.TOT10, 0);

        let newRow = td_cons({
            CANTIDAD: objTable.CANTIDAD,
            DESCRIPCION: objTable.DESCRIPCION,
            P_UNITARIO: objTable.P_UNITARIO,
            SUBTOTAL: objTable.SUBTOTAL,
            DEL_BUTTON: objTable.DEL_BUTTON
        });
        if (filtrado.length > 0)
            $(filtrado[0]).html(newRow);
        else {
            let ID_UNICO = objTable.ITEM + "-" + objTable.ID_PRECIO + "-" + objTable.TIPO_PRECIO;
            let nueva_tr = "<tr id='row" + ID_UNICO + "'>" + newRow + "</tr>";

            $(" #grill-ticket").append(nueva_tr);
        }
        calcularTotalesVuelto();
    }


    //cargar items a la tabla de venta
    function cargar(itemData) { //ID STOCK O IDPRECIO ,  MODUS

        //Cantidad inicial
        let cantidad_inicial = 0;
        let encontrado_c_i = ventas_model
            .filter(ar => ar.ITEM == itemData.REGNRO && ar.ID_PRECIO == itemData.ID_PRECIO && ar.TIPO_PRECIO == itemData.TIPO_PRECIO);
        if (encontrado_c_i.length == 0) cantidad_inicial = 0;
        else cantidad_inicial = encontrado_c_i[0].CANTIDAD;

        //Datos necesarios
        let cantidad = parseInt(cantidad_inicial) + 1;
        let desc = itemData.DESCRIPCION;
        let precio_ = itemData.PVENTA;

        let subto_exe = 0;
        let subto_10 = 0;
        let subto_5 = 0;
        let iva = itemData.TRIBUTO;
        if (iva == "0") subto_exe = parseInt(precio_) * cantidad;
        if (iva == "10") subto_10 = parseInt(precio_) * cantidad;
        if (iva == "5") subto_5 = parseInt(precio_) * cantidad;


        let nuevoObjeto = {
            ITEM: itemData.REGNRO,
            CANTIDAD: cantidad,
            DESCRIPCION: desc,
            P_UNITARIO: precio_,
            EXENTA: subto_exe,
            TOT10: subto_10,
            TOT5: subto_5,
            IVA: iva,
            TIPO_PRECIO: itemData.TIPO_PRECIO,
            ID_PRECIO: itemData.ID_PRECIO
        };

        actualizar_modelo(nuevoObjeto);
        actualizar_html_tabla(nuevoObjeto);
        actualizarResumenVenta();
        calcularTotalesVuelto();
    }




    function borrarFila(ev) {

        let padr = ev.parentNode.parentNode;
        let IDrow = padr.id;
        $(padr).remove();
        eliminar_de_modelo(IDrow);
        calcularTotalesVuelto();
    }




    function calcularTotalesVuelto(ev) {

        let valor = 0;
        let total_a_pagar = formValidator.limpiarNumero($("#TOTAL-VENTA").val());
        let bvuelto = 0;
        if ($("#FORMAPAGO").val() == "EFECTIVO") {
            if (ev == undefined) valor = formValidator.limpiarNumero($("input[name=IMPORTE_PAGO]").val());
            else valor = formValidator.limpiarNumero(ev.target.value);

            bvuelto = Math.abs(parseInt(total_a_pagar) - parseInt(valor));
            bvuelto = parseInt(total_a_pagar) > parseInt(valor) ? 0 : bvuelto;
            $("input[name=VUELTO]").val(formatoNumerico.darFormatoEnMillares(bvuelto, 0));

        }

        $("#VENTA-TOTAL-RESUMEN").val(formatoNumerico.darFormatoEnMillares(total_a_pagar, 0));
        $("#VENTA-ENTREGA-RESUMEN").val(formatoNumerico.darFormatoEnMillares(valor, 0));
        $("#VENTA-VUELTO-RESUMEN").val(formatoNumerico.darFormatoEnMillares(bvuelto, 0));

        let total__ = calcular_total_modelo();

        //Verificar existencia de delivery
        let cargos_extra = 0;
        if ($("input[type=hidden][name=DELIVERY]") && $("input[type=hidden][name=DELIVERY]").val() == "S") {
            let id_service= $("select[name=SERVICIO]").val() ;
            cargos_extra = parseInt(  listaDeServicios.filter(ar => ar.REGNRO ==  id_service )[0].COSTO );
        }

        let total_definitivo= total__ + cargos_extra ;
        $("#TOTAL-VENTA").val(formatoNumerico.darFormatoEnMillares( total_definitivo , 0));
    }
</script>
@include("ventas.proceso.detalle.food_gallery.precios.estilos")

<script>
    //Mostrar opcion de precios multiples Cuando hay mas de una variedad 
    function mostrarPreciosParaElegir(STOCK_ID) {
        let itemEncontrado = listaDeProductosJSON.
        filter((ar) => ar.REGNRO == STOCK_ID)[0];

        let variedad_precios = itemEncontrado.precios;
        let rows = variedad_precios.map(({
            REGNRO,
            DESCRIPCION,
            ENTERO,
            MITAD,
            PORCION
        }) => {

            let eventoCargarSegunPrecio = (MODUS) => `onclick='cargarSegunPrecioEspecial( ${REGNRO}, "${MODUS}" )'`; //ID precio  Modo(Entero,Mitad,Porcion)

            let btnEntero = parseInt(ENTERO) > 0 ? ` <button type='button'   ${eventoCargarSegunPrecio('ENTERO')} > ${formatoNumerico.darFormatoEnMillares( ENTERO, 0)} </button>` : "-";
            let btnMitad = parseInt(MITAD) > 0 ? ` <button type='button'   ${eventoCargarSegunPrecio('MITAD')} > ${formatoNumerico.darFormatoEnMillares(MITAD, 0) } </button>` : "-";
            let btnPorcion = parseInt(PORCION) > 0 ? ` <button type='button'  ${eventoCargarSegunPrecio('PORCION')} > ${formatoNumerico.darFormatoEnMillares(PORCION, 0)} </button>` : "-";

            return `<tr> <td>${DESCRIPCION}</td> 
                <td> ${btnEntero} </td> 
                <td>${btnMitad}</td>
                <td> ${btnPorcion} </td>
              </tr>`;
        });

        let html = `
        <div class="container">
        <table id="VENTAS-VARIEDAD-PRECIOS" class="table">
        <thead><tr><th colspan="5">${itemEncontrado.DESCRIPCION}</th>  </tr></thead>
        <thead><tr><th>VARIEDAD</th> <th>ENTERO</th> <th>MITAD</th> <th>PORCIÓN</th></tr></thead>
        <tbody>
            ${rows}
        </tbody>
        </table>
        </div>
            `;
        $("#mymodal .content").html(html);
        $("#mymodal").removeClass("d-none");
    }





    function mostrarPreciosDicotomicos(STOCK_ID) {
        let itemEncontrado = listaDeProductosJSON.
        filter((ar) => String(ar.REGNRO) ==  String( STOCK_ID) )[0];
        console.log("dico",  itemEncontrado);
        
        let pEntero = formatoNumerico.darFormatoEnMillares( itemEncontrado.PVENTA, 0);
        let pMitad = formatoNumerico.darFormatoEnMillares( itemEncontrado.PVENTA_MITAD, 0);

        let html = `
        <div class="container col-12 col-md-4">
        <table id="VENTAS-VARIEDAD-PRECIOS" class="table">
        <thead><tr><th colspan="5">${itemEncontrado.DESCRIPCION}</th>  </tr></thead>
        <thead><tr> </tr></thead>
        <tbody>
           <tr><th>ENTERO</th> <td> <button onclick='cargarSegunPrecioNormal( ${ itemEncontrado.REGNRO})' type='button' >${pEntero}</button></td> </tr>
           <tr> <th>MITAD</th> <td> <button onclick='cargarSegunPrecioMitad( ${itemEncontrado.REGNRO})' type='button' >${pMitad}</button></td></tr>
        </tbody>
        </table>
        </div>
            `;
        $("#mymodal .content").html(html);
        $("#mymodal").removeClass("d-none");
    }






    function cargarSegunPrecioNormal(paramStockId) {
        //Se recibio el ID de producto
        let datosItem = {};
        Object.assign(datosItem, listaDeProductosJSON.filter((ar) => ar.REGNRO == paramStockId)[0]);
        datosItem.TIPO_PRECIO = "NORMAL";
        datosItem.ID_PRECIO = "NORMAL";
        cargar(datosItem);
        if ("cerrarMyModal" in window)
            cerrarMyModal();
    }

    function cargarSegunPrecioMitad(paramStockId) {
        //Se recibio el ID de producto
        let datosItem = {};
        let targetObject = listaDeProductosJSON.filter((ar) => ar.REGNRO == paramStockId)[0];
        Object.assign(datosItem, targetObject);
        datosItem.PVENTA = targetObject.PVENTA_MITAD;
        datosItem.DESCRIPCION=  targetObject.DESCRIPCION+" (MITAD)";
        datosItem.TIPO_PRECIO = "MITAD";
        datosItem.ID_PRECIO = "MITAD";
        cargar(datosItem);
        if ("cerrarMyModal" in window)
            cerrarMyModal();
    }

    function cargarSegunPrecioEspecial(paramIdPrecio, modus) {
        //Se recibio ID precio y modus(ENTERO, MITAD, PORCION)

        var soloPrecios = listaDeProductosJSON.map((ar) => ar.precios).reduce(
            (arrcum, arrpre) => {
                arrpre.forEach(val => {
                    arrcum.push(val);
                });
                return arrcum;
            }, []);

        let datoPrecio = soloPrecios.filter((ar) => ar.REGNRO == paramIdPrecio)[0];

        //Descubrir id de stock
        let datosItem = {};
        Object.assign(datosItem, listaDeProductosJSON
            .filter((ar) => String(ar.REGNRO) == String(datoPrecio.STOCK_ID))[0]);
        //modificar precio
        let nuevoPrecio = datosItem.precios
            .filter(ar => ar.REGNRO == paramIdPrecio)[0][modus];

        datosItem.DESCRIPCION = datosItem.DESCRIPCION + "(" + datoPrecio.DESCRIPCION + ")";
        datosItem.PVENTA = nuevoPrecio;
        datosItem.ID_PRECIO = paramIdPrecio;
        datosItem.TIPO_PRECIO = modus;
        cargar(datosItem);
        if ("cerrarMyModal" in window)
            cerrarMyModal();
    }
</script>
 
<script>
    //Mostrar opcion de precios multiples Cuando hay mas de una variedad 
    function mostrarPreciosParaElegir(STOCK_ID) {
        //Obtener modelo de datos con id STOCK_ID
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








    

    function mostrarPreciosVarios(STOCK_ID) {
        let itemEncontrado = listaDeProductosJSON.
        filter((ar) => String(ar.REGNRO) ==  String( STOCK_ID) )[0];
        
        let opcionesPrecio= itemEncontrado.precios.filter( ar => ar.PRECIO != undefined && ar.PRECIO != 0).map(

            (precioOpc)=>
          {
          
            let precio_ = formatoNumerico.darFormatoEnMillares( precioOpc.PRECIO, 0);
                let callback= precioOpc.DESCRIPCION == "NORMAL" ?  `cargarSegunPrecioNormal(${ itemEncontrado.REGNRO})` : `cargarSegunPreciosVarios(${ itemEncontrado.REGNRO}, "${ precioOpc.DESCRIPCION}" )`;
              return ` <tr><th>${precioOpc.DESCRIPCION}</th> <td> <button onclick='${callback}' type='button' >${precio_}</button></td> </tr>`}
        );
        
        
        let html = `
        <div class="container col-12 col-md-4">
        <table id="VENTAS-VARIEDAD-PRECIOS" class="table">
        <thead><tr><th colspan="5">${itemEncontrado.DESCRIPCION}</th>  </tr></thead>
        <thead><tr> </tr></thead>
        <tbody>
          ${opcionesPrecio}
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

    function cargarSegunPreciosVarios(paramStockId, tipoPrecio) {
        //Se recibio el ID de producto
        let datosItem = {};
        let targetObject = listaDeProductosJSON.filter((ar) => ar.REGNRO == paramStockId)[0];
        Object.assign(datosItem, targetObject);
        
        let precioSeleccionado= 0;
        switch( tipoPrecio){
            case "NORMAL": precioSeleccionado=  targetObject.PVENTA;break;
            case "MITAD": precioSeleccionado=  targetObject.PVENTA_MITAD;break;
            case "EXTRA": precioSeleccionado=  targetObject.PVENTA_EXTRA;break;
        }
        datosItem.PVENTA = precioSeleccionado;
        datosItem.DESCRIPCION=  targetObject.DESCRIPCION+" ("+tipoPrecio+")";
        datosItem.TIPO_PRECIO = tipoPrecio;
        datosItem.ID_PRECIO = tipoPrecio;

        cargar(datosItem);
        if ("cerrarMyModal" in window)
            cerrarMyModal();
    }
    /*
    function cargarSegunPreciosVarios(paramStockId) {
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
    }*/

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
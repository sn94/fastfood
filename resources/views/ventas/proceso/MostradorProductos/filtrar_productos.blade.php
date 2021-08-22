<input style="font-size: 1.5em;" class="mt-0 w-100" autocomplete="off" id="search" type="text" placeholder="ESCRIBE EL NOMBRE, O CÓDIGO DE PRODUCTO A BUSCAR" oninput="filtrarProductos(event)">


<script>
    var celdaResaltadaDom = "";

    function filtrarProductos(ev) {
        $(".resaltado").removeClass("resaltado");

        let quitarResaltado = function() {
            $(celdaResaltadaDom).removeClass("resaltado");
            $(".resaltado").removeClass("resaltado"); 
            //   $(celdaResaltadaDom + " span.descripcion").css("color", "white"); 
        };
        let agregarResaltado = function() {
            $(celdaResaltadaDom).addClass("resaltado");  

        };

        let buscado = ev.target.value;
        if (buscado == "") {

            quitarResaltado();
            return;
        }
        let resultados = listaDeProductosJSON.filter(
            ar => {
                let minusDesc = ar.DESCRIPCION.toLowerCase();
                let minusCod = ar.CODIGO.toLowerCase();
                let minusCodBar = ar.BARCODE.toLowerCase();


                let minusBusc = buscado.toLowerCase();

                return minusDesc.includes(minusBusc) || minusCod.includes(minusBusc) || minusCodBar.includes(minusBusc);
            }
        );

        if (resultados.length > 0) {

            //solo leer uno
            let primero = resultados[0];
            //obtener familia pestana
            let pestana = primero.FAMILIA;
            //NOMBRE PANEL
            let panel = "#" + pestana + "-tab";
            $(panel).click();
            //resaltar
            let celda = "#FOOD-CELL-" + primero.REGNRO;

            //deshacer efecto del anterior seleccionado
            quitarResaltado();

            //nuevo item resaltado
            celdaResaltadaDom = celda;
            agregarResaltado();

        }

    }
</script>
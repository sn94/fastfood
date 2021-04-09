<div class=" col-12 col-md-6 bg-light">
    <canvas id="grafico1" width="100" height="100"></canvas>
</div>

<script>
    /**
     * 
     * dom element target
     * 
     * dataSet (labels y datos)
     * labelX
     * labelY
     * title
     * 
     */

    //Los mas comprado por determinada sucursal
    async function grafProveedoresFrecuentes() {

       
        let requestData = await fetch("<?= url('compra/estadis-proveedores-frecuentes') ?>", {
            method: "POST",
            headers: {
                'Content-type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'formato': 'json'
            },
            body: JSON.stringify({ 
            })
        });
        let responseData = await requestData.json();
        //cORTAR
        let titulo= responseData.titulo;
        let datos = responseData.data;

        let labels = datos.map(ar => ar.PROVEEDOR_NOMBRE);
        let cantidades =  datos.map(ar => {
            return ar.NUMERO_COMPRAS;
        });



        //Obtener labels
        // Any of the following formats may be used 
        var ctx = document.getElementById('grafico1').getContext('2d');

        dibujarGraficoDeBarras(ctx, {
            labels: labels,
            datos: cantidades,
            labelX: "PROVEEDORES",
            labelY: "COMPRAS",
            title: titulo,
            generalLabel: 'Numero de compras'

        });


    }
</script>
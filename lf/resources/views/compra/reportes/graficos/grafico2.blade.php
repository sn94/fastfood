<div class=" col-12 col-md-6 bg-light">
    <canvas id="grafico2" width="100" height="100"></canvas>
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
    async function grafLoMasComprado() {

        let sucursal= "<?=session('SUCURSAL')?>";
        let requestData = await fetch("<?= url('compra/filtrar') ?>", {
            method: "POST",
            headers: {
                'Content-type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'formato': 'json'
            },
            body: JSON.stringify({
                FILTRO: 2,
                SUCURSAL: sucursal
            })
        });
        let responseData = await requestData.json();
        //cORTAR
        console.log(responseData);
        let cincoReg = responseData.slice(0, 5);
        let labels = cincoReg.map(ar => ar.DESCRIPCION);
        let cantidades = cincoReg.map(ar => {
            return ar.CANTIDAD;
        });



        //Obtener labels
        // Any of the following formats may be used 
        var ctx = document.getElementById('grafico2').getContext('2d');

        dibujarGraficoDeBarras(ctx, {
            labels: labels,
            datos: cantidades,
            labelX: "PRODUCTOS",
            labelY: "CANTIDADES",
            title: "LO MAS COMPRADO EN ESTE LOCAL",
            generalLabel: 'Cantidades compradas'

        });


    }
</script>
<div class=" col-12 col-md-4 bg-light">
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

    
    async function grafProductosMasPedidos() {

       
        let requestData = await fetch("<?= url('stock/filtrar') ?>", {
            method: "POST",
            headers: {
                'Content-type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'formato': 'json'
            },
            body: JSON.stringify({ 
                DESCRIPTIVO: "S",
                FILTRO: 1
            })
        });
        let responseData = await requestData.json();
        let filtrados =  responseData.data.filter(

            function(  objeto, index, arr ){

                let elMayorNumero= arr.filter( ar=>ar.SUCURSAL_ID == objeto.SUCURSAL_ID)
                .map( ar=> ar.NUMERO_PEDIDOS).reduce( (acum, val)=>{
                    if( val > acum) return val;
                    else return acum;
                });
                return ( elMayorNumero ==  objeto.NUMERO_PEDIDOS);
 
            }
        );
     
        //cORTAR
        let titulo= responseData.titulo;
        let datos = filtrados;

        let labels = datos.map(ar => `SUC.${ ar.SUCURSAL_ID} ${ar.DESCRIPCION}`  ); 

        let cantidades =  datos.map(ar => {
            return ar.NUMERO_PEDIDOS;
        });



        //Obtener labels
        // Any of the following formats may be used 
        var ctx = document.getElementById('grafico1').getContext('2d');

        dibujarGraficoDeBarras(ctx, {
            labels: labels,
            datos: cantidades,
            labelX: "SUCURSALES Y PRODUCTOS",
            labelY: "NÚMERO DE PEDIDOS",
            title: titulo,
            generalLabel: 'Numero de pedidos'

        });


    }
</script>
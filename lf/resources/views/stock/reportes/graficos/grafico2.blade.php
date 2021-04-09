<div class=" col-12 col-md-4 bg-light text-dark">
<label>
   Filtrar por   Sucursal:
    </label>
    <x-sucursal-chooser class="form-control form-control-sm"  value=""  name="SUCURSAL-GRAFICO2" id="SUCURSAL-GRAFICO2" callback="grafProductosMasPedidosLocalmente()" style=""></x-sucursal-chooser>
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

     window.GRAFICO2= undefined;
    
    async function grafProductosMasPedidosLocalmente() {

       
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
                FILTRO: 1,
                SUCURSAL:  $("#SUCURSAL-GRAFICO2").val()

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
        console.log("filtrado", filtrados);
        //cORTAR
        let titulo= responseData.titulo;
        let datos = filtrados;

        let labels = datos.map(ar => `SUC.${ ar.SUCURSAL_ID} ${ar.DESCRIPCION}`  ); 

        let cantidades =  datos.map(ar => {
            return ar.NUMERO_PEDIDOS;
        });



        //Obtener labels
        // Any of the following formats may be used 
        var ctx = document.getElementById('grafico2');
        var contexto = ctx.getContext("2d");
        contexto.clearRect(0, 0, ctx.width, ctx.height);
        
       // contexto = document.getElementById('grafico2').getContext('2d');

       if( window.GRAFICO2 != undefined)  window.GRAFICO2.destroy();

     window.GRAFICO2=    dibujarGraficoDeBarras( contexto, {
            labels: labels,
            datos: cantidades,
            labelX: "PRODUCTOS",
            labelY: "NÚMERO DE PEDIDOS",
            title: titulo,
            generalLabel: 'Numero de pedidos'

        });


    }
</script>
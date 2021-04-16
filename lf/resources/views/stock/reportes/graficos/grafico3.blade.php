<div class=" col-12 col-md-4 bg-light text-dark">
<label>
   Filtrar por   Sucursal:
    </label>
    <x-sucursal-chooser class="form-control form-control-sm"  value=""  name="SUCURSAL-GRAFICO3" id="SUCURSAL-GRAFICO3" callback="grafResiduosDeIngredientes()" style=""></x-sucursal-chooser>
    <canvas id="grafico3" width="100" height="100"></canvas>
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

     window.GRAFICO3= undefined;
    
    async function grafResiduosDeIngredientes() {

       
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
                FILTRO: 2,
                SUCURSAL:  $("#SUCURSAL-GRAFICO3").val()

            })
        });
        let responseData = await requestData.json();
       
        
        //cORTAR
        let titulo= responseData.titulo;
        let datos = responseData.data;

        let labels = datos.map(ar => ` ${ar.DESCRIPCION}`  ); 

        let cantidades =  datos.map(ar => {
            return ar.NUMERO_RESIDUOS;
        });



        //Obtener labels
        // Any of the following formats may be used 
        var ctx = document.getElementById('grafico3');
        var contexto = ctx.getContext("2d");
        contexto.clearRect(0, 0, ctx.width, ctx.height);
        
       // contexto = document.getElementById('grafico3').getContext('2d');

       if( window.GRAFICO3 != undefined)  window.GRAFICO3.destroy();

     window.GRAFICO3=    dibujarGraficoDeBarras( contexto, {
            labels: labels,
            datos: cantidades,
            labelX: "INGREDIENTES",
            labelY: "NÚMERO DE NOTAS DE RESIDUO",
            title: titulo,
            generalLabel: 'Numero de notas de residuos'

        });


    }
</script>
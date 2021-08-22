<div class=" col-12 col-md-4 bg-light text-dark">
<label>
   Filtrar por   Sucursal:
    </label>
    <x-sucursal-chooser class="form-control form-control-sm"  value=""  name="SUCURSAL-GRAFICO2" id="SUCURSAL-GRAFICO2" callback="grafMediosDePagoPreferido()" style=""></x-sucursal-chooser>
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
    
    async function grafMediosDePagoPreferido() {

       
        let requestData = await fetch("<?= url('ventas/filtrar') ?>", {
            method: "POST",
            headers: {
                'Content-type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'formato': 'json'
            },
            body: JSON.stringify({ 
                DESCRIPTIVO: "S",
                FILTRO: 3,
                SUCURSAL:  $("#SUCURSAL-GRAFICO2").val()

            })
        });
        let responseData = await requestData.json();
         
      
        //cORTAR
        let titulo= responseData.titulo;
       

        let labels =  responseData.data.map(ar =>  ar.FORMA  ); 

        let cantidades =  responseData.data.map(ar => {
            return parseInt( ar.NUMERO_VENTAS );
        });



        //Obtener labels
        // Any of the following formats may be used 
        var ctx = document.getElementById('grafico2');
        var contexto = ctx.getContext("2d");
        contexto.clearRect(0, 0, ctx.width, ctx.height);
        
       // contexto = document.getElementById('grafico2').getContext('2d');

       if( window.GRAFICO2 != undefined)  window.GRAFICO2.destroy();

     window.GRAFICO2=  dibujarGraficoTorta(  contexto, {
            labels: labels,
            datos: cantidades,
            labelX: "MEDIOS DE PAGO",
            labelY: "NÚMERO DE VENTAS",
            title: titulo,
            generalLabel: 'Numero de ventas'

        });


    }
</script>
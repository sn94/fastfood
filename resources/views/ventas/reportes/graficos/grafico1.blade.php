 <div class=" col-12 col-md-4 bg-light text-dark">
     <label>
         Filtrar por Sucursal:
     </label>
     <x-sucursal-chooser class="form-control form-control-sm" value="" name="SUCURSAL-GRAFICO1" id="SUCURSAL-GRAFICO1" callback="grafProductosMasVendidos()" style=""></x-sucursal-chooser>

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


      window.GRAFICO1= undefined;

     async function grafProductosMasVendidos() {


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
                 FILTRO: 1,
                 SUCURSAL: $("#SUCURSAL-GRAFICO1").val()
             })
         });
         let responseData = await requestData.json();
         

         //cORTAR
         let titulo = responseData.titulo;
         let subData=  responseData.data.slice(0, 5);
          

         let labels = subData.map(ar => `${ar.DESCR_CORTA}`);

         let cantidades = subData.map(ar => {
             return ar.NUMERO_VENTAS;
         });



         //Obtener labels
         // Any of the following formats may be used 
         var ctx = document.getElementById('grafico1').getContext('2d');

         if( window.GRAFICO1 != undefined)  window.GRAFICO1.destroy();

            window.GRAFICO1=  dibujarGraficoTorta(  ctx , {
             labels: labels,
             datos: cantidades,
             labelX: "PRODUCTOS",
             labelY: "NÚMERO DE VENTAS",
             title: titulo,
             generalLabel: 'Numero de ventas'

         });
         //dibujarGraficoDeBarras(ctx, );


     }
 </script>
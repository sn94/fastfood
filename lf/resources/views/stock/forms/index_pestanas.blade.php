 @php
 use App\Helpers\Utilidades;



 $REGNRO= isset( $stock )? $stock->REGNRO : "";
 $CODIGO= isset( $stock )? $stock->CODIGO : "";
 $BARCODE= isset( $stock )? $stock->BARCODE : "";
 $BOTON= isset( $stock )? $stock->BOTON : "";

 $DESCRIPCION= isset( $stock )? $stock->DESCRIPCION : "";
 $IVA= isset( $stock )? $stock->TRIBUTO : "10";
 $FAMILIA= isset( $stock )? $stock->FAMILIA : "";
 $FAMILIA_NOM= isset( $stock )? ( is_null($stock->familia ) ? '' : $stock->familia->DESCRIPCION ): "";
 $PVENTA= isset( $stock )? Utilidades::number_f( $stock->PVENTA ) : "0";
 $PVENTA_MITAD= isset( $stock )? Utilidades::number_f( $stock->PVENTA_MITAD) : "0";
 $PCOSTO= isset( $stock )? Utilidades::number_f( $stock->PCOSTO ) : "0";
 $STOCK_MAX= isset( $stock )? Utilidades::number_f( $stock->STOCK_MAX ): "0";
 $STOCK_MIN= isset( $stock )? Utilidades::number_f($stock->STOCK_MIN) : "0";

 $ULT_COMPRA= isset( $stock )? $stock->ULT_COMPRA : "";
 $TIPO= isset( $stock )? $stock->TIPO : "";
 $TIPO_P= $TIPO=='P'? 'checked':'';
 $TIPO_F= $TIPO=='F'? 'checked':'';
 $MEDIDA= isset( $stock )? $stock->MEDIDA : "UNIDAD";


 //Source
 use App\Models\Familia;

 $FAMILIAS= Familia::get();

 $PRESENTACIONES= [ "ENTERO", "MITAD", "PORCION"];

 @endphp


 <style>
     input:disabled {
         background-color: #7d7d7d !important;
     }

     .MEDIDA {
         color: #222;
         font-weight: 600;
     }

     a i.fa-search,
     a i.fa-download {
         background-color: #f7fb55;
         border-radius: 30px;
         padding: 5px;
         border: 1px solid black;
         color: black;
     }

     .form-control {
         background: white !important;
         color: black !important;
         height: 28px !important;
         font-size: 12.5px;
     }



     label {
         font-size: 14px !important;
         color: white;
     }

     .div-inline-30-70 {
         display: grid;
         grid-template-columns: 30% 70%;
         margin-bottom: 2px;
     }

     .div-inline-40-60 {
         display: grid;
         grid-template-columns: 40% 60%;
         margin-bottom: 2px;
     }

     [class*="div-inline"] label {
         grid-column-start: 1;
     }

     [class*="div-inline"] input {
         grid-column-start: 2;
     }

     #STOCKRECETA thead tr th,
     #STOCKRECETA tbody tr td {
         padding: 0px;
         font-family: mainfont;
         font-size: 15px;
         font-weight: 600;
     }
 </style>


 <input type="hidden" id="FAMILIA-URL" value="{{url('familia')}}">
 @if( $REGNRO != "")
 <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
 @endif
 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


 <div class="row">
     <div class="col-3 col-md-1 mr-3 mr-md-3 ">
         <button type="submit" class="btn btn-warning btn-sm">GUARDAR</button>
     </div>
     <div class="col-12">

         <div id="accordion bg-dark">

              <!--DATOS PRINCIPALES DE PRODUCTO-->
              <div class="card bg-dark">
                 <div class="card-header" id="headingMain">
                     <h5 class="mb-0">
                         <button type="button"  class="btn btn-link" data-toggle="collapse" data-target="#collapseBasicos" aria-expanded="true" aria-controls="collapsePrecios">
                             DATOS PRINCIPALES
                         </button>
                     </h5>
                 </div>

                 <div id="collapseBasicos" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                     <div class="card-body">
                         @include("stock.forms.form_basico")
                         @include("stock.forms.form_stock")
                     </div>
                 </div>
             </div>

            
            

             <!--RECETA Y FOTO ILUSTRATIVA-->
             <div class="card bg-dark">
                 <div class="card-header" id="headingReceta">
                     <h5 class="mb-0">
                         <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseReceta" aria-expanded="true" aria-controls="collapsePrecios">
                             RECETA - INGREDIENTES PARA ELABORACIÓN
                         </button>
                     </h5>
                 </div>

                 <div id="collapseReceta" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                     <div class="card-body">
                         @include("stock.forms.form_foto")
                         
                         @include("stock.forms.form_receta")
                     </div>
                 </div>
             </div>

              <!--PRECIOS para quien lo quiera configurar
              <div class="card bg-dark">
                 <div class="card-header" id="headingPrecios">
                     <h5 class="mb-0">
                         <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapsePrecios" aria-expanded="true" aria-controls="collapsePrecios">
                             CONFIGURACIÓN DE PRECIOS DE VENTA POR VARIEDAD DE PRODUCTO
                         </button>
                     </h5>
                 </div>

                 <div id="collapsePrecios" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                     <div class="card-body">
                         @ include("stock.forms.form_precios")
                     </div>
                 </div>
             </div>
             -->


             
         </div> <!--End accordeon-->
     </div>
 </div><!--Fin fila-->



 @include("validations.form_validate")
 @include("validations.formato_numerico")


 <script>
     //mostrar imagen seleccionada
     function show_loaded_image(ev) {
         let entrada = ev.srcElement;
         console.log(entrada);
         let reader = new FileReader();
         reader.onload = function(e) {
             var filePreview = document.createElement('img');
             filePreview.className = "img-fluid";
             filePreview.src = e.target.result;
             filePreview.style.width = "200px";
             filePreview.style.maxHeight = "200px";
             let tagDestination = "#" + ev.target.name;
             var previewZone = document.querySelector(tagDestination);
             previewZone.innerHTML = "";
             previewZone.appendChild(filePreview);
         };
         reader.readAsDataURL(entrada.files[0]);
     }



     /*
     function recuperar_datos_modal() {
         $('.modal').on('hidden.bs.modal', function(e) {
             $("#FORM-STOCK-ITEM-ID").val(buscador_items_modelo.REGNRO);
             $("#ITEM").val(buscador_items_modelo.DESCRIPCION);
             $("#MEDIDA").text(buscador_items_modelo.MEDIDA);
             //cargar_tabla();
         });
     }*/




     async function guardarStock(ev) {
         ev.preventDefault();

        
         formValidator.init(ev.target);
         show_loader();
         let req = await fetch(ev.target.action, {
             "method": "POST",
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             body: new FormData(ev.target)
         });
         let resp = await req.json();
         hide_loader();
         if ("ok" in resp) {
             alert(resp.ok);

             $("#STOCKIMG").attr("src", "");
             let tipo_item = $("select[name=TIPO]").val();
             if ($("#REDIRECCIONAR").val() == "SI")
                 window.location = "<?= url('stock/buscar') ?>/" + tipo_item;
         } else {
             alert(resp.err);
         }
     }






     window.onload = function() {


         if ("stockModel" in window && "restaurar_modelo_receta" in stockModel)
             stockModel.restaurar_modelo_receta();
         //recuperar_datos_modal();
         /*Formato numerico **/
         //formato entero
         formatoNumerico.formatearCamposNumericosDecimales("STOCKFORM");
     };
 </script>
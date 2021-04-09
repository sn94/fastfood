<?php

$MODULO_FLAG=    isset($_GET['m']) ? $_GET['m'] :  "" ;
$QUERY_FLAG=  $MODULO_FLAG == "c" ? "?m=c"  :  ""; 

?>

 @php
 use App\Helpers\Utilidades;



 $REGNRO= isset( $stock )? $stock->REGNRO : "";
 $CODIGO= isset( $stock )? $stock->CODIGO : "";
 $BARCODE= isset( $stock )? $stock->BARCODE : "";
 $BOTON= isset( $stock )? $stock->BOTON : "";

 $DESCRIPCION= isset( $stock )? $stock->DESCRIPCION : "";
 $DESCR_CORTA= isset( $stock )? $stock->DESCR_CORTA : "";
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
 $SUCURSAL=  isset( $stock )? $stock->SUCURSAL : session("SUCURSAL");
 $VENDIDOXMITAD= isset( $stock )? $stock->VENDIDOXMITAD  : "N";
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
 

 <input type="hidden" name="SUCURSAL" value="{{$SUCURSAL}}">
 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


 <div class="row">
     <div class="col-12">
         <button type="submit" class="btn btn-warning btn-sm">GUARDAR</button>
 
  
         <x-pretty-checkbox   name="VENDIDOXMITAD" :value="$VENDIDOXMITAD" onValue="S"  offValue="N"  label="VENDIDO POR MITAD:" />
  
  
  
     </div>


     <div class="col-12 col-sm-6 col-md-6 pb-2">
     @include("stock.forms.form_basico")
     </div>
     <div class="col-12 col-sm-6 col-md-6 pb-2 ">
     @include("stock.forms.form_stock")
     </div>
     <div class="col-12 col-sm-6 col-md-6  ">
     @include("stock.forms.form_foto")
     </div>

     <div class="col-12 col-sm-6 col-md-6  ">
     @include("stock.forms.form_receta")
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
         //Metodo 
         let metodo=   $("input[name=_method]").val(); 

         let req = await fetch(ev.target.action, {
             "method":  metodo,
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             body: new FormData(ev.target)
         });
         let resp = await req.json();
         hide_loader();
         if ("ok" in resp) {
             alert( "Guardado");

             //lIMPIAR IMAGEN
             $("#STOCKIMG").attr("src", "");

             
             let stockObject=   Object.assign(  resp.ok );
             window.ULTIMO_STOCK=  stockObject;

             //TIpo de item para listado
             let tipo_item = $("select[name=TIPO]").val();
             if ($("#REDIRECCIONAR").val() == "SI")
                 window.location = "<?= url('stock/buscar') ?>/" + tipo_item+"<?=$QUERY_FLAG?>";
            else{
                //Cerrar modal si hubiere
                if( "cerrarMyModal" in window)   cerrarMyModal();
            }
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
 @php
 use App\Helpers\Utilidades;



 $REGNRO= isset( $materiaprima )? $materiaprima->REGNRO : "";
 $CODIGO= isset( $materiaprima )? $materiaprima->CODIGO : "";
 $BARCODE= isset( $materiaprima )? $materiaprima->BARCODE : "";
 $BOTON= isset( $materiaprima )? $materiaprima->BOTON : "";
 $IMG= isset( $materiaprima )? $materiaprima->IMG : "";
 $DESCRIPCION= isset( $materiaprima )? $materiaprima->DESCRIPCION : "";
 $IVA= isset( $materiaprima )? $materiaprima->TRIBUTO : "10";
 $FAMILIA= isset( $materiaprima )? $materiaprima->FAMILIA : "";
 $PVENTA= isset( $materiaprima )? Utilidades::number_f( $materiaprima->PVENTA ) : "";
 $PCOSTO= isset( $materiaprima )? Utilidades::number_f( $materiaprima->PCOSTO ) : "";
 $STOCKTOTAL= isset( $materiaprima )? $materiaprima->STOCKTOTAL : "";
 $STOCK_MIN= isset( $materiaprima )? $materiaprima->STOCK_MIN : "";
 $PROVEEDOR= isset( $materiaprima )? $materiaprima->PROVEEDOR : "";
 $PROVEEDOR_NOM= isset( $materiaprima )? ( is_null($materiaprima->proveedor) ? '' : $materiaprima->proveedor->NOMBRE ): "";
 $ULT_COMPRA= isset( $materiaprima )? $materiaprima->ULT_COMPRA : "";
 $MEDIDA= isset( $producto )? $producto->MEDIDA : "UNIDAD";



 @endphp


 <style>
     .form-control {
         background: white !important;
         color: black !important;
         height: 40px !important;
     }

     label {
         font-size: 18px !important;
         color: white;
     }
 </style>



 <input type="hidden" id="PROVEEDOR-URL" value="{{url('proveedores')}}">
 <div class="row">

     <div class="col-12 offset-md-2 col-md-8">
         <div class="row bg-dark pb-2 pr-2 pl-2 pr-md-2 pl-md-2">
             <div class="col-12 col-md-6">


                 @if( $REGNRO != "")
                 <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
                 @endif

                 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


                 <label>CÓDIGO</label>
                 <input name="CODIGO" class="form-control" type="text" maxlength="15" value="{{$CODIGO}}" />

                 <label>DESCRIPCIÓN </label>
                 <input name="DESCRIPCION" class="form-control" type="text" maxlength="80" value="{{$DESCRIPCION}}" />



                 <div class="mt-1" style="display: grid; grid-template-columns: 40% 60%;">
                     <label style="grid-column-start: 1;">IVA: </label>
                     <select style="grid-column-start: 2;" name="TRIBUTO" class="form-control">
                         @php
                         $option_iva= [0=> "EXENTA", 10=> "10 %", 5=> "5 %" ];
                         @endphp

                         @foreach( $option_iva as $num => $val)
                         @if( $num == $IVA)
                         <option selected value="{{$num}}"> {{$val}} </option>
                         @else
                         <option value="{{$num}}"> {{$val}} </option>
                         @endif
                         @endforeach
                     </select>
                 </div>

                 <label>PRECIO DE COSTO </label>
                 <input numerico="yes" oninput="formatear_entero(event)" name="PCOSTO" class="form-control text-right" type="text" maxlength="80" value="{{$PCOSTO}}" />





             </div>
             <div class="col-12 col-md-6">

                 <label for="element_5">STOCK MÍNIMO </label>
                 <input numerico="yes" oninput="formatear_entero(event)" name="STOCK_MIN" class="form-control" type="text" maxlength="20" value="{{$STOCK_MIN}}" />

                 <label for="element_6">STOCK TOTAL </label>
                 <input numerico="yes" oninput="formatear_entero(event)" name="STOCKTOTAL" class="form-control" type="text" maxlength="20" value="{{$STOCKTOTAL}}" />

                 <div style="display: grid; grid-template-columns: 30% 70%;">
                     <label style="grid-column-start: 1;" class="mt-1" for="element_7">PROVEEDOR </label>
                     <input type="hidden" name="PROVEEDOR" value="{{$PROVEEDOR}}">
                     <input style="grid-column-start: 2;" class="form-control proveedor mt-1" type="text" value="{{$PROVEEDOR_NOM}}" />
                 </div>

                 <label for="element_7">ÚLTIMA COMPRA </label>
                 <input name="ULT_COMPRA" class="form-control" type="date" value="{{$ULT_COMPRA}}" />

                 <div class="mt-1" style="display: grid; grid-template-columns: 40% 60%;">
                     <label style="grid-column-start: 1;">UNIDAD DE MEDIDA: </label>
                     <select style="grid-column-start: 2;" name="MEDIDA" class="form-control">
                         @php
                         $option_med= [ "UNIDAD", "KILO", "GRAMO", "PAQUETE"];
                         @endphp

                         @foreach( $option_med as $val)
                         @if( $val == $MEDIDA)
                         <option selected value="{{$val}}"> {{$val}} </option>
                         @else
                         <option value="{{$val}}"> {{$val}} </option>
                         @endif
                         @endforeach
                     </select>
                 </div>

             </div>



             <div class="col-12 mt-2 d-flex justify-content-center">
                 <button type="submit" class="btn btn-warning btn-lg">GUARDAR</button>
             </div>


         </div>
     </div>
 </div>



 <script>
     //Sin puntuacion
     function solo_numeros(ev) {
         //0 48   9 57
         if (ev.data == null) return;
         if ((ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57)) {
             let cad = ev.target.value;
             let cad_n = cad.substr(0, ev.target.selectionStart - 1) + cad.substr(ev.target.selectionStart + 1);
             ev.target.value = cad_n;
         }
     }




     function restaurar_sep_miles() {
         let nro_campos_a_limp = $("[numerico=yes]").length;

         for (let ind = 0; ind < nro_campos_a_limp; ind++) {
             let valor = $("[numerico=yes]")[ind].value;
             let valor_forma = dar_formato_millares(valor);
             $("[numerico=yes]")[ind].value = valor_forma;
         }
         //return val.replaceAll(new RegExp(/[.]*/g), "");
     }

     function limpiar_numero(val) {
         let nro_campos_a_limp = $("[numerico=yes]").length;

         for (let ind = 0; ind < nro_campos_a_limp; ind++) {
             let valor = $("[numerico=yes]")[ind].value;
             let valor_purifi = valor.replaceAll(new RegExp(/[.]*/g), "");
             $("[numerico=yes]")[ind].value = valor_purifi;
         }
         //return val.replaceAll(new RegExp(/[.]*/g), "");
     }


     function dar_formato_millares(val_float) {
         return new Intl.NumberFormat("de-DE").format(val_float);
     }

     function formatear_decimal(ev) { //

         if (ev.data == undefined) {
             ev.target.value = "0";
             return;
         }
         if (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) {
             let noEsComa = ev.data.charCodeAt() != 44;
             let yaHayComa = ev.data.charCodeAt() == 44 && /(,){1}/.test(ev.target.value.substr(0, ev.target.value.length - 2));
             let comaPrimerLugar = ev.data.charCodeAt() == 44 && ev.target.value.length == 1;
             let comaDespuesDePunto = ev.data.charCodeAt() == 44 && /\.{1},{1}/.test(ev.target.value);
             if (noEsComa || (yaHayComa || comaPrimerLugar || comaDespuesDePunto)) {
                 ev.target.value = ev.target.value.substr(0, ev.target.selectionStart - 1) + ev.target.value.substr(ev.target.selectionStart);
                 return;
             } else return;
         }
         //convertir a decimal
         //dejar solo la coma decimal pero como punto 
         let solo_decimal = limpiar_numero_para_float(ev.target.value);
         let noEsComaOpunto = ev.data.charCodeAt() != 44 && ev.data.charCodeAt() != 46;
         if (noEsComaOpunto) {
             let float__ = parseFloat(solo_decimal);

             //Formato de millares 
             let enpuntos = dar_formato_millares(float__);
             $(ev.target).val(enpuntos);
         }
     }


     function formatear_entero(ev) {

         //       if (ev.data == undefined) return;
         if (ev.data == null || ev.data == undefined)
             ev.target.value = ev.target.value.replaceAll(new RegExp(/[.]*[,]*/g), "");
         if (ev.data != null && (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57)) {

             ev.target.value =
                 ev.target.value.substr(0, ev.target.selectionStart - 1) +
                 ev.target.value.substr(ev.target.selectionStart);
         }
         //Formato de millares
         let val_Act = ev.target.value;
         val_Act = val_Act.replaceAll(new RegExp(/[.]*[,]*/g), "");
         let enpuntos = new Intl.NumberFormat("de-DE").format(val_Act);

         try {
             if (parseInt(enpuntos) == 0) $(ev.target).val("");
             else $(ev.target).val(enpuntos);
         } catch (err) {
             $(ev.target).val(enpuntos);
         }

     }










     //mostrar imagen seleccionada
     function show_loaded_image(ev) {
         let entrada = ev.srcElement;
         console.log(entrada);
         let reader = new FileReader();
         reader.onload = function(e) {
             var filePreview = document.createElement('img');
             filePreview.className = "img-thumbnail";
             filePreview.src = e.target.result;
             filePreview.style.width = "100%";
             filePreview.style.maxHeight = "100%";
             let tagDestination = "#" + ev.target.name;
             var previewZone = document.querySelector(tagDestination);
             previewZone.innerHTML = "";
             previewZone.appendChild(filePreview);
         };
         reader.readAsDataURL(entrada.files[0]);
     }




     //Autocomplete
     async function autocompletado_proveedores() {
         let url_ = $("#PROVEEDOR-URL").val();
         let req = await fetch(url_, {
             headers: {
                 formato: "json"
             }
         });
         let resp = await req.json();

         var dataArray = resp.map(function(value) {
             return {
                 label: value.NOMBRE,
                 value: value.REGNRO
             };
         });

         let elementosCoincidentes = document.querySelectorAll(".proveedor");

         Array.prototype.forEach.call(elementosCoincidentes, function(input) {
             new Awesomplete(input, {
                 list: dataArray,
                 // insert label instead of value into the input.
                 replace: function(suggestion) {
                     this.input.value = suggestion.label;
                     $("input[name=PROVEEDOR]").val(suggestion.value);
                 }
             });
         });

     }



     window.onload = function() {
         autocompletado_proveedores();
     };
 </script>
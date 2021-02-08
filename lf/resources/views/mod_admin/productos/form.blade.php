 @php
 use App\Helpers\Utilidades;



 $REGNRO= isset( $producto )? $producto->REGNRO : "";
 $CODIGO= isset( $producto )? $producto->CODIGO : "";
 $BARCODE= isset( $producto )? $producto->BARCODE : "";
 $BOTON= isset( $producto )? $producto->BOTON : "";
 $IMG= isset( $producto )? $producto->IMG : "";
 $DESCRIPCION= isset( $producto )? $producto->DESCRIPCION : "";
 $IVA= isset( $producto )? $producto->TRIBUTO : "10";
 $FAMILIA= isset( $producto )? $producto->FAMILIA : "";
 $FAMILIA_NOM= isset( $producto )? ( is_null($producto->familia ) ? '' : $producto->familia->DESCRIPCION ): "";
 $PVENTA= isset( $producto )? Utilidades::number_f( $producto->PVENTA ) : "";
 $PCOSTO= isset( $producto )? Utilidades::number_f( $producto->PCOSTO ) : "";
 $STOCK_MAX= isset( $producto )? $producto->STOCK_MAX : "0";
 $STOCK_MIN= isset( $producto )? $producto->STOCK_MIN : "0";
  
 $ULT_COMPRA= isset( $producto )? $producto->ULT_COMPRA : "";
 $TIPO= isset( $producto )? $producto->TIPO : "";
 $TIPO_P= $TIPO=='P'? 'checked':'';
 $TIPO_F= $TIPO=='F'? 'checked':'';
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



 
 <input type="hidden" id="FAMILIA-URL" value="{{url('familia')}}">


 <div class="row">

     <div class="col-12 offset-md-2 col-md-8">
         <div class="row bg-dark pb-2 pr-2 pl-2 pr-md-2 pl-md-2">
             <div class="col-12 col-md-6">


                 @if( $REGNRO != "")
                 <input type="hidden" name="REGNRO" value="{{$REGNRO}}">
                 @endif

                 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">




                 <div class="input-group mb-3">
                     <div class="input-group-prepend">
                         <span class="input-group-text">Buscar</span>
                     </div>
                     <div class="custom-file">
                         <input type="file" name="IMG" onchange="show_loaded_image(event)" class="custom-file-input" id="inputGroupFile01">

                         <label class="custom-file-label" for="inputGroupFile01">Cargue una imagen</label>
                     </div>
                 </div>


                 <div id="IMG" style="min-height: 250px;">
                     <img class="img-fluid" src="{{url($IMG)}}" alt="">
                 </div>
                 <label>CÓDIGO</label>
                 <input name="CODIGO" class="form-control" type="text" maxlength="15" value="{{$CODIGO}}" />

                 <label>CÓDIGO DE BARRAS </label>
                 <input name="BARCODE" class="form-control" type="text" maxlength="15" value="{{$BARCODE}}" />


                 <label>DESCRIPCIÓN </label>
                 <input name="DESCRIPCION" class="form-control" type="text" maxlength="80" value="{{$DESCRIPCION}}" />


             </div>
             <div class="col-12 col-md-6">


                 <div style="display: grid; grid-template-columns: 40% 60%;">
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


                 <div style="display: grid;  grid-template-columns: 20%  80%;">
                     <label style="grid-column-start: 1;" class="mt-1" for="element_7">FAMILIA: </label>
                     <input type="hidden" name="FAMILIA" value="{{$FAMILIA}}">
                     <input oninput="if(this.value=='') document.querySelector('input[name=FAMILIA]').value='' ; " style="grid-column-start: 2;" class="form-control familia mt-1" type="text" value="{{$FAMILIA_NOM}}" />
                 </div>


                 <label>PRECIO DE VENTA </label>
                 <input numerico="yes" oninput="formatear_entero(event)" name="PVENTA" class="form-control text-right" type="text" maxlength="80" value="{{$PVENTA}}" />

                 <label>PRECIO DE COSTO </label>
                 <input numerico="yes" oninput="formatear_entero(event)" name="PCOSTO" class="form-control text-right" type="text" maxlength="80" value="{{$PCOSTO}}" />


                 <label for="element_5">STOCK MÍNIMO </label>
                 <input numerico="yes" oninput="formatear_entero(event)" name="STOCK_MIN" class="form-control" type="text" maxlength="20" value="{{$STOCK_MIN}}" />

                 <label for="element_6">STOCK MÁXIMO </label>
                 <input numerico="yes" oninput="formatear_entero(event)" name="STOCK_MAX" class="form-control" type="text" maxlength="20" value="{{$STOCK_MAX}}" />

               

                 <label for="element_7">ÚLTIMA COMPRA </label>
                 <input name="ULT_COMPRA" class="form-control" type="date" value="{{$ULT_COMPRA}}" />

                 <label for="element_7">TIPO: </label>
                 <div class="form-check form-check-inline">

                     <input {{$TIPO_P}} class="form-check-input" type="radio" name="TIPO" id="inlineRadio1" value="P">
                     <label class="form-check-label" for="inlineRadio1">PREVENTA</label>
                 </div>
                 <div class="form-check form-check-inline">
                     <input {{$TIPO_F}} class="form-check-input" type="radio" name="TIPO" id="inlineRadio2" value="F">
                     <label class="form-check-label" for="inlineRadio2">PREPARADO</label>
                 </div>

                 <div style="display: grid; grid-template-columns: 40% 60%;">
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
     async function autocompletado_familias() {
         let url_ = $("#FAMILIA-URL").val();
         let req = await fetch(url_, {
             headers: {
                 formato: "json"
             }
         });
         let resp = await req.json();

         var dataArray = resp.map(function(value) {
             return {
                 label: value.DESCRIPCION,
                 value: value.REGNRO
             };
         });

         let elementosCoincidentes = document.querySelectorAll(".familia");

         Array.prototype.forEach.call(elementosCoincidentes, function(input) {
             new Awesomplete(input, {
                 list: dataArray,
                 // insert label instead of value into the input.
                 replace: function(suggestion) {
                     this.input.value = suggestion.label;
                     $("input[name=FAMILIA]").val(suggestion.value);
                 }
             });
         });

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
         autocompletado_familias();
      //   autocompletado_proveedores();
     };
 </script>
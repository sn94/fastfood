 <form id="APROBACION-SALIDA-FORM" action="{{url('pedidos/aprobar')}}" method="POST">

     @csrf
     <input type="hidden" name="PEDIDO_ID" value="{{$PEDIDO_ID}}">

     <label>Autorizado por: </label>
     <input type="text" name="AUTORIZADO_POR">

     <x-pretty-radio-button name="opcion_aprobacion"  value="APROBADO_COMPLETO" label="Aprobar cantidad solicitada" checked="si" />
     <x-pretty-radio-button name="opcion_aprobacion"  value="CANTIDAD_EDITADA" label="Editar cantidad solicitada" >
     <input type="text" class="decimal" name="CANTIDAD">
     </x-pretty-radio-button>
     <x-pretty-radio-button name="opcion_aprobacion"  value="SIN_STOCK" label=" Sin existencia" />
 
 
     <button type="button" class="btn fast-food-form-button" onclick="aprobar()">ACEPTAR</button>
 </form>

 <script>
     async function aprobar() {
         let objTarget = document.getElementById("APROBACION-SALIDA-FORM");
         formValidator.init(objTarget);
         let url_ = objTarget.action;
         let payload= formValidator.getData("application/json");
         let setting= {
             method: "POST",
             headers: {
                 'Content-Type': 'application/json',
                 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr("content")
             },
             body: JSON.stringify( payload)

         };
 
         let req = await fetch(url_, setting); 
         let resp = await req.json();
         if ("ok" in resp) {
             alert(resp.ok);
             //cerrar modal
             
             $("#PEDIDO-MODAL .modal-body").html("");
             $("#PEDIDO-MODAL").modal("hide");
             fill_grill();
         } else
             alert(resp.err);
     }
 </script>
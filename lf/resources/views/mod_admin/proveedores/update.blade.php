@extends("templates.app_admin")


@section("content")



<input type="hidden" id="PROVEEDORES-INDEX"  value="{{url('proveedores')}}" >


 
<a class="btn btn-lg btn-warning" href="<?= url("proveedores")?>"> Lista de proveedores</a>

<h2 class="text-center mt-2" style="font-family: titlefont;">Actualizar datos de cliente</h2>


<div id="loaderplace"></div>
<form action="{{url('proveedores')}}"  method="POST"   onsubmit="guardar(event)">
  
        
        <input type="hidden" name="_method"  value="PUT">
        @include("mod_admin.proveedores.form")
     

</form>


<script>

async function guardar(ev) {
        ev.preventDefault();
        //if (campos_vacios()) return;
 
        $("input[name=CEDULA_RUC]").val(limpiar_numero($("input[name=CEDULA_RUC]").val()));
     

        show_loader();
        let req = await fetch(ev.target.action, {
            "method": "POST",
            headers: {
            
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: $(ev.target).serialize()
        });
        let resp = await req.json();
        hide_loader();
        if ("ok" in resp) {
            alert(  resp.ok);
            window.location=  $("#PROVEEDORES-INDEX").val() ;
        } else {
            $("input[name=CEDULA_RUC]").val(dar_formato_millares($("input[name=CEDULA_RUC]").val()));
          
            alert(  resp.err);
        }


    }
</script>
@endsection
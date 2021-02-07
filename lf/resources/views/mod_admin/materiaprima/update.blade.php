@extends("templates.app_admin")


@section("content")



<input type="hidden" id="materia-prima-INDEX"  value="{{url('materia-prima')}}" >


 
<a class="btn btn-lg btn-warning" href="<?= url("materia-prima")?>"> Listado de materia prima</a>

<h2 class="text-center mt-2" style="font-family: titlefont;">Actualizar datos de materia prima</h2>


<div id="loaderplace"></div>
<form action="{{url('materia-prima')}}"  method="POST"   onsubmit="guardar(event)" enctype="multipart/form-data" >
  
        
        <input type="hidden" name="_method"  value="PUT">
        @include("mod_admin.materiaprima.form")
     

</form>


<script>

function show_loader() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
    }

    function hide_loader() {
        $("#loaderplace").html("");
    }




async function guardar(ev) {
        ev.preventDefault();
        //if (campos_vacios()) return;
 
        $("input[name=CEDULA_RUC]").val(limpiar_numero($("input[name=CEDULA_RUC]").val()));
     

        show_loader();
        let req = await fetch(ev.target.action, {
            "method": "POST",
            
            headers: {
            
               
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              
            },
            body:   new FormData(   ev.target )
        });
        let resp = await req.json();
        hide_loader();
        if ("ok" in resp) {
            alert(  resp.ok);
           // window.location=  $("#materia-prima-INDEX").val() ;
        } else {
            $("input[name=CEDULA_RUC]").val(dar_formato_millares($("input[name=CEDULA_RUC]").val()));
          
            alert(  resp.err);
        }


    }
</script>
@endsection
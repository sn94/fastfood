<?php

 
?>
@extends("templates.caja.index")

@section("content")


@include("ventas.proceso.res.impresion")


<div class="container-fluid bg-dark text-light col-12 col-lg-10  pb-2 mt-2 ">
<h3 class="text-center">Ventas diarias</h3>


<div class="container"  id="grill">

  @include("ventas.index.grill")
</div>

</div>

<script>
  async function fill_grill() {


    let grill_url ="<?=url('ventas/index')?>";
    

    let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
    $("#grill").html(loader);
    let req = await fetch(grill_url, {

      headers: {
        'X-Requested-With': "XMLHttpRequest"
      }
    });
    let resp = await req.text();
    $("#grill").html(resp);

  }
</script>


@endsection
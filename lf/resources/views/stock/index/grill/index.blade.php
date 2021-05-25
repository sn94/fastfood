<?php

use App\Libs\Mobile_Detect;
?>

@if( (new Mobile_Detect())->isMobile() )

@include("stock.index.grill.mobile")
@else
@include("stock.index.grill.desktop")
@endif

<x-pretty-paginator  :datos="$stock"  callback='buscarStock(event);' />
 



<script>
  async function delete_row(ev) {
    ev.preventDefault();
    if (!confirm("continuar?")) return;
    let req = await fetch(ev.currentTarget.href, {
      "method": "DELETE",
      headers: {

        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      body: "_method=DELETE&_token=" + $('meta[name="csrf-token"]').attr('content')

    });
    let resp = await req.json();
    if ("ok" in resp) buscarStock();
    else alert(resp.err);

  }

  window.onload = function() {

    //  window.location = "<?= url('stock') ?>";
  };
</script>
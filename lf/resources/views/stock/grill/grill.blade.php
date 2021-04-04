<?php

use App\Libs\Mobile_Detect;
?>











@if( (new Mobile_Detect())->isMobile() )

@include("stock.grill.mobile")
@else
@include("stock.grill.desktop")
@endif


<script>
    window.onload = function() {

      //  window.location = "<?= url('stock') ?>";
    };
</script>
 
<?php

use App\Libs\Mobile_Detect;

$responsive= new Mobile_Detect();
if(  $responsive->isMobile()):
    echo view('clientes.grill.mobile',  ['clientes' =>   $clientes]);
else: 
    echo view('clientes.grill.desktop',  ['clientes' =>   $clientes]);
endif;
?>


<script>

window.onload= function(){

    window.location=  "<?=url('clientes')?>";
};
</script>
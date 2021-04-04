<?php

use App\Libs\Mobile_Detect;

if ((new Mobile_Detect())->isMobile()) :
    echo view("proveedores.grill.mobile", ['proveedores' =>  $proveedores]);
else :
    echo view("proveedores.grill.desktop",  ['proveedores' =>  $proveedores]);
endif;

?>

<script>
    window.onload = function() {

        window.location = "<?= url('proveedores') ?>";
    };
</script>
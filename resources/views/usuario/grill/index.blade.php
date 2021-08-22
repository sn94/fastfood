<?php

use App\Libs\Mobile_Detect;

if((new Mobile_Detect())->isMobile()):
    echo view("usuario.grill.mobile", ['usuarios'=>  $usuarios]);
    else:
    echo view("usuario.grill.desktop",  ['usuarios'=>  $usuarios]);
    endif;

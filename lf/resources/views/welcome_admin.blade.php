
@extends("templates.admin.index")


@section("content")


<?php

use App\Libs\Mobile_Detect;

$fondoResponsive= (new Mobile_Detect());
 
?>


<x-user-info-box></x-user-info-box>

@if(  $fondoResponsive->isMobile() )
<img style="width: 100%;height: 100%;" src="<?=url('assets/images/fastfood_wallpaper_mobile.jpg')?>" alt="">
@else 
<img style="width: 100%;height: 100%;" src="<?=url('assets/images/fastfood_wallpaper.jpg')?>" alt="">
@endif 


@endsection
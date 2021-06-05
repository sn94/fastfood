 <!DOCTYPE html>
 <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" manifest="{{url('fastfood_cache.manifest')}}">

 <head>

     <title> @yield("PageTitle", "Fast Food")</title>
     <meta charset="utf-8">
     <link rel="manifest" href="{{url('manifest.json')}}" />
     <script src="{{url('/')}}/install_sw.js?v={{rand()*1000}}"> </script>


     <link rel="apple-touch-icon" href="{{url('assets/icons/maskable/burger144.png')}}" />
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <meta name="theme-color" content="#a01809" />
     <link rel="icon" href="{{url('assets/icons/burger_icon.png')}}" />

     @php
     $BASE_ASSETS= url('assets');
     @endphp
     <!--Carga temprana de jquery -->
     <script src="{{$BASE_ASSETS}}/js/jquery.min.js"></script>
     <script src="{{$BASE_ASSETS}}/bootstrap5/bootstrap.bundle.min.js"> </script>
       <!--Jquery and bootstrap js load End --> 

     <link rel="stylesheet" href="{{$BASE_ASSETS}}/bootstrap5/bootstrap.min.css" />
     <link rel="stylesheet" href="{{$BASE_ASSETS}}/fontawesome/fontawesome.min.css" />
     <link rel="stylesheet" href="{{$BASE_ASSETS}}/fontawesome/solid.min.css" />
     <link rel="stylesheet" href="{{$BASE_ASSETS}}/fontawesome/brands.min.css" />
     
     <!--Estilos Personalizados segun el entorno -->
     @php
     $themeActual= Illuminate\Support\Facades\Config::get("app.my_config.themes.selected");
     $estilosTheme= Illuminate\Support\Facades\Config::get("app.my_config.themes.$themeActual.estilos");
     @endphp
     @foreach( $estilosTheme as $style)
     <link rel="stylesheet" href="{{$style}}" />
     @endforeach

     <script src="{{$BASE_ASSETS}}/js/custom.js?v={{rand()*1000}}"> </script>
<!--Javascript -->

     @yield("cssStyles")

 </head>

 <body class="h-100 p-0">

     @include("templates.mymodal" )

     @yield("menu")
     <button class="backward-button" onclick="window.history.go(-1);" class="button">Atrás</button>

     <!--  style="background-image: url( $BASE_ASSETS /images/bg_1.jpg);" -->
     @yield("content")
     @yield('jsScripts')
 </body>

 </html>
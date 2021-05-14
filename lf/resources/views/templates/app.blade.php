 <!DOCTYPE html>
 <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

 <head>
     <title> @yield("PageTitle", "Fast Food")</title>
     <meta charset="utf-8">

     <link rel="icon" href="{{url('assets/icons/burger_icon.png')}}">

     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}">




     @php
     $BASE_ASSETS= url('assets');
     @endphp


     <!--Carga temprana de jquery -->
     <script src="{{$BASE_ASSETS}}/js/jquery.min.js"></script>
     <script src="{{$BASE_ASSETS}}/bootstrap5/bootstrap.bundle.min.js"> </script>

     <link rel="stylesheet" href="{{$BASE_ASSETS}}/bootstrap5/bootstrap.min.css">
     <link rel="stylesheet" href="{{$BASE_ASSETS}}/fontawesome/fontawesome.min.css">
     <link rel="stylesheet" href="{{$BASE_ASSETS}}/fontawesome/solid.min.css">
     <link rel="stylesheet" href="{{$BASE_ASSETS}}/fontawesome/brands.min.css">
     <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/custom.css?v={{rand()*1000}}">

     <script>
         navigator.sayswho = (function() {
             var ua = navigator.userAgent,
                 tem,
                 M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];


             if (/trident/i.test(M[1])) {
                 tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
                 return 'IE ' + (tem[1] || '');
             }
             if (M[1] === 'Chrome') {
                 tem = ua.match(/\b(OPR|Edge)\/(\d+)/);
                 if (tem != null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
             }
             M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
             if ((tem = ua.match(/version\/(\d+)/i)) != null) M.splice(1, 1, tem[1]);
             return M.join(' ');
         })();


         Date.prototype.getFecha = function() {
             let elmes = parseInt(this.getMonth()) + 1;
             elmes = elmes < 10 ? "0" + elmes : elmes;
             let eldia = parseInt(this.getDate());
             eldia = eldia < 10 ? "0" + eldia : eldia;
             return this.getFullYear() + "-" + elmes + "-" + this.getDate();
         }

         function replaceAll_compat() {
             if (!("replaceAll" in String.prototype)) {
                 let replaceAll = function(expre_reg, substitute) {
                     return this.replace(expre_reg, substitute);
                 };
                 String.prototype.replaceAll = replaceAll;
             }
         }
         replaceAll_compat();


         function show_loader() {

             let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
             $("#loaderplace").html(loader);
         }

         function hide_loader() {
             $("#loaderplace").html("");
         }


         function get_fast_food_identification_for_module_context() {
             return document.getElementById("fast-food-identification-for-module-context").value;
         }
     </script>
 </head>

 <body class="h-100">


     @include("templates.mymodal" )

     @yield("menu")


     <!--  style="background-image: url( $BASE_ASSETS /images/bg_1.jpg);" -->




     @yield("content")

     @yield('jsScripts')
 </body>

 </html>
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
     <link rel="stylesheet" href="{{$BASE_ASSETS}}/bootstrap5/bootstrap.min.css">
     <link rel="stylesheet" href="{{$BASE_ASSETS}}/fontawesome/fontawesome.min.css">
     <link rel="stylesheet" href="{{$BASE_ASSETS}}/fontawesome/solid.min.css">
     <link rel="stylesheet" href="{{$BASE_ASSETS}}/fontawesome/brands.min.css">
     <script src="{{$BASE_ASSETS}}/bootstrap5/bootstrap.bundle.min.js"> </script>
     <link rel="stylesheet" href="{{$BASE_ASSETS}}/awesomplete.css">



     <style>
        
        html{
            height: 100%;
        }

        :root{
            --bs-warning:  #ffb74d !important;
        }
        .bg-warning{
            background-color: var(--bs-warning)  !important;
        }
        .btn-warning{
            background-color: var(--bs-warning)  !important;
        }




         @import url("<?= url('assets/fonts/Marvel-Regular.ttf') ?>");
         @import url("<?= url('assets/fonts/HARLOWSI.ttf') ?>");
         @import url("<?= url('assets/fonts/Michroma.ttf') ?>");

         @font-face {
             font-family: "mainfont";
             src: url("<?= url('assets/fonts/Marvel-Regular.ttf') ?>");

         }

         @font-face {
             font-family: titlefont;
             src: url("<?= url('assets/fonts/Michroma.ttf') ?>");
         }
 
          

         .ftco-footer {
             /* padding: 7em 0; */
             padding: 3px;
             height: 50px !important;
         }


     </style>


     <script>
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
     </script>
 </head>

 <body class="h-100">


     @include("templates.mymodal" )

     @yield("menu")


     <!--  style="background-image: url( $BASE_ASSETS /images/bg_1.jpg);" -->


   

         @yield("content") 








     @yield("Footer" )




     <script src="{{$BASE_ASSETS}}/awesomplete.min.js"></script>




     @yield("jsScripts")
 </body>

 </html>
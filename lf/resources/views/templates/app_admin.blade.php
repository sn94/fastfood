<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Pizza</title>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do" rel="stylesheet">

    @php
    $BASE_ASSETS= url('assets');
    @endphp
    <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/animate.css">

    <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/magnific-popup.css">

    <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/aos.css">

    <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/ionicons.min.css">

    <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/jquery.timepicker.css">
    <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/flaticon.css">
    <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/icomoon.css">
    <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/style.css">
    <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{$BASE_ASSETS}}/awesomplete.css">
   


    <style>
        @import url("<?= url('assets/fonts/Marvel-Regular.ttf') ?>");
        @import url("<?= url('assets/fonts/HARLOWSI.ttf') ?>");

        @font-face {
            font-family: "mainfont";
            src: url("<?= url('assets/fonts/Marvel-Regular.ttf') ?>");

        }
        @font-face {
            font-family: "titlefont";
         
            src: url("<?= url('assets/fonts/HARLOWSI.TTF') ?>");

        }

        h1,
        h2,
        h3,
        h4,
        h4,
        h5,
        h6{
            font-family: titlefont;
        }
        label{
            font-family: mainfont;
        }


        /* auto com */
        .autocomplete-suggestion{
            background-color: #d1d78e;
            color: black;
            font-weight: 600;
        }
    </style>
       
    
    <script>

function reset_form( form) {
    let fields=  form.elements;

    Array.prototype.forEach.call(   fields, function( ele){

        if(ele.type== "text")   ele.value="";
     } ); 
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

<body>


    <!--NAV BAR -->

    @include("templates.menu_admin")
    <!-- END nav -->

<!--  style="background-image: url( $BASE_ASSETS /images/bg_1.jpg);" -->
    <section class="ftco-intro">
        @yield("content")
    </section>









    <footer class="ftco-footer ftco-section img">
        <div class="row">
            <div class="col-md-12 text-center">

                <p>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    Copyright &copy;
                    <script>
                        document.write(new Date().getFullYear());
                    </script>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                </p>
            </div>
        </div>
    </footer>



    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" />
        </svg></div>


    <script src="{{$BASE_ASSETS}}/js/jquery.min.js"></script>
    <script src="{{$BASE_ASSETS}}/js/jquery-migrate-3.0.1.min.js"></script>
    <script src="{{$BASE_ASSETS}}/js/popper.min.js"></script>
    <script src="{{$BASE_ASSETS}}/js/bootstrap.min.js"></script>
    <script src="{{$BASE_ASSETS}}/js/jquery.easing.1.3.js"></script>
    <script src="{{$BASE_ASSETS}}/js/jquery.waypoints.min.js"></script>
    <script src="{{$BASE_ASSETS}}/js/jquery.stellar.min.js"></script>
    <script src="{{$BASE_ASSETS}}/js/owl.carousel.min.js"></script>
    <script src="{{$BASE_ASSETS}}/js/jquery.magnific-popup.min.js"></script>
    <script src="{{$BASE_ASSETS}}/js/aos.js"></script>
    <script src="{{$BASE_ASSETS}}/js/jquery.animateNumber.min.js"></script>
    <script src="{{$BASE_ASSETS}}/js/bootstrap-datepicker.js"></script>
    <script src="{{$BASE_ASSETS}}/js/jquery.timepicker.min.js"></script>
    <script src="{{$BASE_ASSETS}}/js/scrollax.min.js"></script>
    <script src="{{$BASE_ASSETS}}/js/main.js"></script>
    <script src="{{$BASE_ASSETS}}/js/fontawesome.js"></script>
    <script src="{{$BASE_ASSETS}}/awesomplete.min.js"></script>

   

</body>

</html>
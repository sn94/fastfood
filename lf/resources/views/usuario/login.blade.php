@php
$BASE_ASSETS= url('assets');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Fast Food</title>
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


    <style>
        body {

            background-color: black !important;
            background-image: url(<?= url("assets/images/bg-fastfood-login.png") ?>);
            background-size: cover;
            background-repeat: no-repeat;
            height: 100vh;
        }



        .form-control {
            background: #79797980 !important;
            color: white;

        }



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
        h6 {
            font-family: titlefont;
        }

        label {
            font-family: mainfont;
        }


        /* auto com */
        .autocomplete-suggestion {
            background-color: #d1d78e;
            color: black;
            font-weight: 600;
        }
    </style>


    <script>
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

<body class="d-flex flex-row justify-content-center"> 


       <div class="container-fluid d-flex "  style="background-color: #6a3d13a8;">
       <div class="container pt-5 pt-md-3 pb-5 pb-md-3 col-12 col-md-6 col-lg-4 align-self-center rounded" id="FORM-PANEL"  style="background-color: black;">
            <form  class="text-light m-0 p-0 p-md-2 " onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="login(event)" action="{{url('usuario/sign-in')}}" method="POST" style=" display: flex; flex-direction: column;">

            <div class="container">
            <img  class="img-fluid" src="{{url('assets/images/logo.png')}}" alt="">
                @csrf



                <label class="text-center" style="font-size: 18px;">NICK: </label>
                <input name="USUARIO" class="form-control text-light" type="text" />
                <label class="text-center" style="font-size: 18px;">PASSWORD: </label>
                <input name="PASS" class="form-control text-light" type="password" />

                <button class="btn btn-warning mt-2 w-100">INGRESAR</button>
            </div>
            </form>
        </div>
       </div>


 

    <script src="{{$BASE_ASSETS}}/js/jquery.min.js"></script>



    <script>
        async function login(ev) {
            ev.preventDefault();
            let req = await fetch(ev.target.action, {

                method: "POST",
                headers: {
                    'Content-Type': "application/x-www-form-urlencoded",
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: $(ev.target).serialize()
            });
            let resp = await req.json();
            if ("ok" in resp)
                window.location = resp.ok;
            else
                alert(resp.err);
        }
    </script>
</body>

</html>
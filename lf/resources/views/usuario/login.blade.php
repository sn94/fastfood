@php
$BASE_ASSETS= url('assets');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Fast Food</title>
    <meta charset="utf-8">
    <link rel="manifest" href="{{url('manifest.json')}}" />
    <link rel="apple-touch-icon" href="{{url('assets/icons/maskable/burger144.png')}}" />
    <meta name="theme-color" content="#a01809" />
    <link rel="icon" href="{{url('assets/icons/burger_icon.png')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
    $BASE_ASSETS= url('assets');
    @endphp

    <!--Carga temprana de jquery -->
    <script src="{{$BASE_ASSETS}}/js/jquery.min.js"></script>
    <link rel="stylesheet" href="{{$BASE_ASSETS}}/bootstrap5/bootstrap.min.css">
    <link rel="stylesheet" href="{{$BASE_ASSETS}}/css/custom.css?v={{rand()*1000}}">
    <script src="{{$BASE_ASSETS}}/js/custom.js?v={{rand()*1000}}"> </script>
    <script src="{{url('/')}}/install_sw.js?v={{rand()*1000}}"> </script>


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
            color: white !important;
        }



        #login-button {
            background: var(--color-primario) !important;
            border: 0.5px solid white;
            padding: 10px !important;
            color: white;
            font-size: 1em;
        }

        #login-button:hover,
        #login-button:active {
            border: 5px solid var(--color-primario);
            color: white;
            text-shadow: 0 0 5px white;
            box-shadow: 0 0 10px var(--color-primario);
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


    <div class="container-fluid d-flex " style="background-color: #6a3d13a8;">
        <div class="container pt-5 pt-md-3 pb-5 pb-md-3 col-12 col-md-6 col-lg-4 align-self-center rounded" id="FORM-PANEL" style="background-color: black;">
            <form class="  m-0 p-0 p-md-2 " onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="login(event)" action="{{url('usuario/sign-in')}}" method="POST" style=" display: flex; flex-direction: column;">

                <div class="container d-flex flex-column justify-content-center">

                    @if( Illuminate\Support\Facades\Config::get("app.my_config.themes.selected") == "AlEstiloPecchi")
                    <img class="img-fluid" src="{{url('assets/images/logo.png')}}" alt="">
                    @endif
                    @if(  Illuminate\Support\Facades\Config::get("app.my_config.themes.selected") == "FunnyOrange")
                    <img style="width: 100px !important; height: auto !important;" class="img-fluid" src="{{url('assets/icons/burger_icon.png')}}" alt="">
                    @endif



                    @csrf



                    <label class="text-center text-light" style="font-size: 18px;">NICK: </label>
                    <input name="USUARIO" class="form-control  " type="text" />
                    <label class="text-center text-light" style="font-size: 18px;">PASSWORD: </label>
                    <input name="PASS" class="form-control  " type="password" />

                    <div class=" mt-2   mx-auto">
                        <button id="login-button">INGRESAR</button>
                    </div>
                </div>
            </form>
        </div>
    </div>







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
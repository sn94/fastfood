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
        var filtrarBrowser= /safari/i;
        if(  filtrarBrowser.test(navigator.sayswho)  )
        alert( "El navegador utilizado no es compatible con las funcionalidades que requiere el sistema. (Navegadores compatibles: Firefox, Edge, Chrome y Opera)");

    </script>

  

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

      

#login-button{
background: var(--color-3) !important;
border: 0.5px solid white;
padding:  10px !important;
color: white;
font-size: 1em;
}

#login-button:hover,  #login-button:active{  
border: 5px solid var(--color-3); 
color: white;
text-shadow: 0 0 5px white;
box-shadow: 0 0 10px   var(--color-3);
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
            <form class="text-light m-0 p-0 p-md-2 " onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="login(event)" action="{{url('usuario/sign-in')}}" method="POST" style=" display: flex; flex-direction: column;">

                <div class="container d-flex flex-column justify-content-center">
                    <img class="img-fluid" src="{{url('assets/images/logo.png')}}" alt="">
                    @csrf



                    <label class="text-center" style="font-size: 18px;">NICK: </label>
                    <input name="USUARIO" class="form-control text-light" type="text" />
                    <label class="text-center" style="font-size: 18px;">PASSWORD: </label>
                    <input name="PASS" class="form-control text-light" type="password" />

                    <div class=" mt-2   mx-auto">
                    <button id="login-button"  >INGRESAR</button>
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
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Pizza</title>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">



    @php
    $BASE_ASSETS= url('assets');
    @endphp


    <style>
        /* Extra small devices (phones, 600px and down) */
        @media only screen and (max-width: 600px) {
            body {
                display: grid;
                grid-template-columns: 5% 90% 5%;

            }

            body>a:nth-child(1) {
                grid-column-start: 2;
            }

            body>a:nth-child(2) {
                grid-column-start: 2;
            }


        }

        /* Small devices (portrait tablets and large phones, 600px and up) */
        @media only screen and (min-width: 600px) {
            body {
                display: grid;
                grid-template-columns: 20% 60% 20%;

            }

            body>a:nth-child(1) {
                grid-column-start: 2;
            }

            body>a:nth-child(2) {
                grid-column-start: 2;
            }
        }

        /* Medium devices (landscape tablets, 768px and up) */
        @media only screen and (min-width: 768px) {
            body {
                display: grid;
                grid-template-columns: 20% 60% 20%;

            }

            body>a:nth-child(1) {
                grid-column-start: 2;
            }

            body>a:nth-child(2) {
                grid-column-start: 2;
            }
        }

        /* Large devices (laptops/desktops, 992px and up) */
        @media only screen and (min-width: 992px) {

            body {
                display: grid;
                grid-template-columns: 40%  20% 40%;

            }

            body>a:nth-child(1) {
                grid-column-start: 2;
            }

            body>a:nth-child(2) {
                grid-column-start: 2;
            }

        }

        /* Extra large devices (large laptops and desktops, 1200px and up) */
        @media only screen and (min-width: 1200px) {}













        a {
            background: rgb(10, 7, 1);
            background: linear-gradient(0deg, rgba(10, 7, 1, 1) 0%, rgba(253, 187, 45, 1) 100%);
            margin-bottom: 30px;
            padding: 50px;
            font-size: 35px;
            font-family: mainfont;
            text-decoration: none;
            text-align: center;
            color: wheat;
            border-radius: 20px;
        }



        @import url("<?= url('assets/fonts/Marvel-Regular.ttf') ?>");
        @import url("<?= url('assets/fonts/delicata.ttf') ?>");

        @font-face {
            font-family: "mainfont";
            src: url("<?= url('assets/fonts/Marvel-Regular.ttf') ?>");

        }

        @font-face {
            font-family: "titlefont";

            src: url("<?= url('assets/fonts/delicata.ttf') ?>");

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

<body style="background-repeat: no-repeat; background-image: url( <?= url("$BASE_ASSETS/images/bg_1.jpg") ?>  );">


    <a href="{{url('modcaja')}}">Módulo Caja</a>

    <a href="{{url('modadmin')}}">Módulo Administrativo</a>









    <script src="{{$BASE_ASSETS}}/js/jquery.min.js"></script>

</body>

</html>
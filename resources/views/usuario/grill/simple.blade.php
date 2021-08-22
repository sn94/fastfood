<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
</head>

<body>

    <style>
        table thead tr th ,  table tbody tr td {
            padding: 0px;
        }

        table thead tr th  {
         font-size: 11px;
        }
        table tbody tr td  {
         font-size: 10px;
        }
    </style>
    <table>

        <thead style="font-family: mainfont;font-size: 18px;">
            <tr>
                <th>CÉDULA</th>
                <th>USUARIO</th>
                <th>NOMBRE</th>
                <th>SUCURSAL</th>
                <th style="text-align: center;">ORDEN</th>
            </tr>
        </thead>

        <tbody>

            @foreach( $usuarios as $prov)
            <tr>
                <td>{{$prov->CEDULA}}</td>
                <td>{{$prov->USUARIO}}</td>
                <td>{{$prov->NOMBRES}}</td>
                <td>{{$prov->sucursal->DESCRIPCION}}</td>
                <td style="text-align: center;">{{$prov->ORDEN}}</td>
            </tr>
            @endforeach
        </tbody>

    </table>

</body>

</html>
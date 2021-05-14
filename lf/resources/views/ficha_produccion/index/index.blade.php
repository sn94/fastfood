@extends("templates.admin.index")


@section("content")

@php


use App\Models\Ficha_produccion;




$ficha_produc= Ficha_produccion::where("SUCURSAL", session("SUCURSAL") )
->select( "ficha_produccion.*", DB::raw("DATE_FORMAT(FECHA, '%d/%m/%Y') as FECHA"));

if( $ESTADO == "DESPACHADO" && $ACCION =="RESIDUOS")
$ficha_produc= $ficha_produc->where("ESTADO", "DESPACHADO")
->orWhere("ESTADO", "LISTO");
else
$ficha_produc= $ficha_produc->where("ESTADO", $ESTADO);

$ficha_produc=
$ficha_produc->get();

@endphp




<style>
    label {
        font-size: 18px !important;
        color: white;
    }



    table thead tr th,
    table tbody tr td {
        padding: 0px !important;
        color: #414141;
        font-weight: 600;
    }

    /* En línea #20 | http://localhost/fastfood/salida */

    .form-control {
        /* height: 40px !important; */
        height: 30px !important;
        background: white !important;
        color: black !important;
        font-size: 16px;
    }
</style>




<div class="container-fluid bg-dark text-light col-12 col-lg-10 pb-5">

<h2 class="text-center mt-2"  >Órdenes de producción</h2>
<div id="loaderplace"></div>
    <!--BOTONES DE ACCIONES PERSONALIZADAS  -->
    @if( $ESTADO == "PENDIENTE")
    <a class="btn btn-danger mb-1" href="{{url('salida')}}">NUEVA SALIDA</a>
    @endif


    @if( $ESTADO == "PENDIENTE")
    @include("ficha_produccion.index.pendientes")
    @elseif(  $ESTADO == "DESPACHADO")
    @include("ficha_produccion.index.aprobados")
    @elseif(  $ESTADO == "LISTO")
    @include("ficha_produccion.index.listos")
    @endif

</div>
 



<script>
    

    window.onload = function() {


        //formato entero
        let enteros = document.querySelectorAll(".entero");
        Array.prototype.forEach.call(enteros, function(inpu) {
            inpu.oninput = formatoNumerico.formatearEntero;
            $(inpu).addClass("text-end");
        });


        let decimales = document.querySelectorAll(".decimal");
        Array.prototype.forEach.call(decimales, function(inpu) {
            inpu.oninput = formatoNumerico.formatearDecimal;
            $(inpu).addClass("text-end");
        });

    };
</script>

@endsection
@extends("templates.app_caja")


@section("content")


<style>
    #CLIENTE::placeholder {
        color: #000 !important;
        font-weight: 600;
    }


    /* Elemento | http://127.0.0.1:8000/ventas */

    #CLIENTES-RESULTADOS {
        height: 200px;
        background: #fee743;

        z-index: 1000000000000000000;
        position: absolute;
        top: 38px;
        width: 100%;
        left: 30%;
        overflow: auto;
    }

    #CLIENTES-RESULTADOS a {
        color: black;
    }
</style>





<input type="hidden" id="CLIENTES-URL" value="{{url('clientes/buscar')}}">





<h2 class="text-center mt-2" style="font-family: titlefont;">Ventas</h2>


<div id="loaderplace"></div>


<form action="{{url('ventas')}}" method="POST" id="VENTA-FORM">

    @csrf



    <input type="hidden" name="FECHA" value="{{date('Y-m-d')}}">


    <!-- DATOS CLIENTE MODO DE PAGO -->
    <div class="row bg-dark">

        <div class="col-12 col-md-3">
            <div style="display: grid; grid-template-columns: 40% 60%;">
                <label class="text-light" style=" grid-column-start: 1; font-weight: 600; font-size: 20px;  ">Ticket Nro:</label>
                <input type="text" readonly style="grid-column-start: 2;height: 35px; font-weight: 600; color: black; background-color: #f1fe94;">

            </div>
        </div>

        <div class="col-12 col-md-5">
            <label class="text-light" style=" font-weight: 600; font-size: 20px;  ">Cliente:</label>
            <input type="text" id="CLIENTE-RUC" size="3" readonly style="font-weight: 600; color: black; background-color: #f7ff91;">

            <div style="display: inline;">
                <input autocomplete="off" type="text" id="CLIENTE" placeholder="Buscar por nombre, cédula o RUC">
                <div id="CLIENTES-RESULTADOS" class="d-none">
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <label class="text-light" style=" font-weight: 600; font-size: 20px;  ">Forma de pago:</label>
            <select name="FORMA">

                @php
                $option_pago= [ "E"=> "EFECTIVO", "TD"=> "TARJETA DE DÉBITO", "TC"=> "TARJETA DE CRÉDITO", "TIGO MONEY" ];
                @endphp

                @foreach( $option_pago as $num => $val)
                <option value="{{$num}}"> {{$val}} </option>
                @endforeach
            </select>

        </div>


    </div>
    <!--  END DATOS CLIENTE MODO DE PAGO-->







    <!--  CARGA DE TABLA, SELECCION DE MNU -->
    <div class="row bg-dark">

        <div class="col-12 col-md-6  col-lg-6 pr-0">
            @include("mod_caja.ventas.food_gallery")
        </div>

        <div class="col-12  col-md-6 col-lg-6 pl-0">



            <input type="hidden" name="CLIENTE">

            <table class="table table-stripped table-light">
                <thead class="thead-dark">
                    <th>Cantidad</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Exe.</th>
                    <th>10%</th>
                    <th>5%</th>
                </thead>
                <tbody id="grill-ticket" style="font-weight: 600; color: black;">

                </tbody>
                <tfoot style="font-family: mainfont;font-weight: 600;color: black;">
                    <tr style="font-size: 24px;">
                        <td colspan="4"> TOTAL</td>
                        <td id="TOTAL-VENTA" class="text-right" colspan="2"> 0 </td>
                    </tr>
                </tfoot>
            </table>



        </div>

    </div>



</form>


<!--  END  CARGA DE TABLA, SELECCION DE MNU -->

<div class="row">
    <div class="col-12 col-md-4">
        <div class="btn-group" role="group" aria-label="Basic example">

            <button type="button" class="btn btn-secondary">NUEVO</button>
            <button onclick="imprimir()" type="button" class="btn btn-secondary">IMPRIMIR</button>
            <button type="button" class="btn btn-secondary">REIMPRIMIR</button>

        </div>
    </div>
</div>


<div id="grill" style="min-height: 300px;">

</div>


<script>
    function show_loader() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
    }

    function hide_loader() {
        $("#loaderplace").html("");
    }



    function limpiar_numero(val) {
        return val.replaceAll(new RegExp(/[.]*/g), "");
    }



    function dar_formato_millares(val_float) {
        return new Intl.NumberFormat("de-DE").format(val_float);
    }




    //cargar items a la tabla de venta
    function cargar(esto) {

        let id = $(esto).attr("target");
        let existeId = false;
        let cantidad_inicial = 0;
        //buscar id
        let filas = document.querySelectorAll(" #grill-ticket tr ");
        Array.prototype.forEach.call(filas, function(ele) {
            let primero = ele.id;
            if (primero == "row" + id) {
                cantidad_inicial = ele.children[0].textContent;
                existeId = true;
            }
        });

        let cantidad = parseInt(cantidad_inicial) + 1;
        let desc = $("#descr" + id).text();
        let precio = $("#precio" + id).text();
        let precio_ = limpiar_numero($("#precio" + id).text());
        let subto_exe = 0;
        let subto_10 = 0;
        let subto_5 = 0;
        let iva = $("#iva" + id).val();
        if (iva == "0") subto_exe = dar_formato_millares(parseInt(precio_) * cantidad);
        if (iva == "10") subto_10 = dar_formato_millares(parseInt(precio_) * cantidad);
        if (iva == "5") subto_5 = dar_formato_millares(parseInt(precio_) * cantidad);


        let field_item = "<input type='hidden'  name='ITEM[]'  value='" + id + "'   />";
        let field_cantidad = "<input type='hidden'  name='CANTIDAD[]'  value='" + cantidad + "'     />";
        let field_precio = "<input type='hidden'   name='PRECIO[]'   value='" + precio_ + "'    />";
        let foculto = field_item + field_cantidad + field_precio;
        if (existeId)
            $("#row" + id).html(" <td>" + cantidad + "</td>  <td>  " + desc + "</td>   <td class='text-right'>" + precio + "</td>  <td class='text-right'>" + subto_exe + "</td> <td class='text-right'>" + subto_10 + "</td> <td class='text-right'>" + subto_5 + "</td>  " + foculto);
        else {
            let tr = "<tr class='table-warning' id='row" + id + "'>  <td>" + cantidad + "</td>  <td>" + desc + "</td>   <td class='text-right'>" + precio + "</td>  <td class='text-right'>" + subto_exe + "</td> <td class='text-right'>" + subto_10 + "</td> <td class='text-right'>" + subto_5 + "</td> " + foculto + " </tr>";
            $("#grill-ticket").append(tr);
        }

        //calcular total
        filas = document.querySelectorAll(" #grill-ticket tr ");
        let total_venta = 0;
        Array.prototype.forEach.call(filas, function(ele) {
            let subtotal1 = ele.children[3].textContent;
            let subtotal2 = ele.children[4].textContent;
            let subtotal3 = ele.children[5].textContent;
            total_venta += parseInt(limpiar_numero(subtotal1));
            total_venta += parseInt(limpiar_numero(subtotal2));
            total_venta += parseInt(limpiar_numero(subtotal3));
        });
        $("#TOTAL-VENTA").text(dar_formato_millares(total_venta));
    }






    // Preparacion de datos

    function restaurar_sep_miles() {
        let nro_campos_a_limp = $("[numerico=yes]").length;

        for (let ind = 0; ind < nro_campos_a_limp; ind++) {
            let valor = $("[numerico=yes]")[ind].value;
            let valor_forma = dar_formato_millares(valor);
            $("[numerico=yes]")[ind].value = valor_forma;
        }
        //return val.replaceAll(new RegExp(/[.]*/g), "");
    }

    function limpieza_numeros() {
        let nro_campos_a_limp = $("[numerico=yes]").length;

        for (let ind = 0; ind < nro_campos_a_limp; ind++) {
            let valor = $("[numerico=yes]")[ind].value;
            let valor_purifi = valor.replaceAll(new RegExp(/[.]*/g), "");
            $("[numerico=yes]")[ind].value = valor_purifi;
        }

        //return val.replaceAll(new RegExp(/[.]*/g), "");
    }



    async function imprimir() {

        limpieza_numeros();
        let formu = document.getElementById("VENTA-FORM");
        let req = await fetch(formu.action, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: $(formu).serialize()
            }

        );
        let resp = await req.text();
        console.log(resp);
    }




    //Buscar cliente
    //evento seleccionado

    var seleccionado = function(esto) {
        let regnro = $(esto).attr("identidad");
        let ruc = $(esto).attr("ruc");
        let nom = $(esto).attr("nombre");
        $("#CLIENTES-RESULTADOS").addClass("d-none");
        $("input[name=CLIENTE]").val(regnro);
        $("#CLIENTE-RUC").val(ruc);
        $("#CLIENTE").val(nom);

    };




    //Autocomplete
    async function autocompletado_clientes() {
        let termino = "";
        let grill_url = $("#CLIENTES-URL").val();
        // let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        // $("#loaderplace").html(loader);
        let req = await fetch(grill_url, {

            method: "POST",
            headers: {
                'X-Requested-With': "XMLHttpRequest",
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'formato': 'json'
            },
            body: "buscado=" + termino
        });
        let resp = await req.json();
        //  $("#loaderplace").html("");

        var dataArray = resp.map(function(value) {
            return {
                label: "(RUC: "+value.CEDULA_RUC + ") " + value.NOMBRE,
                value: value.REGNRO
            };
        });

        let elementosCoincidentes = document.getElementById("CLIENTE");
        new Awesomplete(elementosCoincidentes, {
            list: dataArray,
            // insert label instead of value into the input.
            replace: function(suggestion) {
                this.input.value = suggestion.label;
                $("input[name=CLIENTE], #CLIENTE-RUC").val(suggestion.value);
            }
        });
        //  Array.prototype.forEach.call(elementosCoincidentes, function(input) {});

    }





    async function buscar_cliente(esto) {
        let termino = esto.value;
        let grill_url = $("#CLIENTES-URL").val();
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
        let req = await fetch(grill_url, {

            method: "POST",
            headers: {
                'X-Requested-With': "XMLHttpRequest",
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'formato': 'json'
            },
            body: "buscado=" + termino
        });
        let resp = await req.json();


        $("#loaderplace").html("");

        //mostrar
        let items_ = resp.map(function(cliente) {

            let regnro = cliente.REGNRO;
            let cedula_ruc = cliente.CEDULA_RUC;
            let nombre = cliente.NOMBRE;
            let item_ = "<a href='#' onclick='seleccionado(this)' nombre='" + nombre + "' ruc='" + cedula_ruc + "'   identidad='" + regnro + "' >" + cedula_ruc + "  " + nombre + "</a><br>";
            return item_;

        });

        if (termino == "" || resp.length == 0)
            $("#CLIENTES-RESULTADOS").addClass("d-none");
        else {
            $("#CLIENTES-RESULTADOS").removeClass("d-none");
            $("#CLIENTES-RESULTADOS").html(items_.join(""));
        }
    }





    window.onload = function() {

        autocompletado_clientes();
    }
</script>
@endsection
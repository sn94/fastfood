<input type="hidden" id="buscador_stock_url" value="{{url('stock/buscar')}}">

@php
$tipos_item= [ "MP" => "MATERIA PRIMA", "PP"=> "PROD. VENTA", "PE"=>"PRODUCTO ELABORADO" , "AF"=> "MOBILIARIO Y OTROS"];
 
@endphp

<div id="buscador_de_items" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">



                @if( !isset($TIPO) )
                Filtrar por:
                <select class="form-control form.buscador" id="BUSCADOR-TIPO-STOCK" onchange="buscar_items__()">
                    @foreach( $tipos_item as $tkey=> $tval)
                    @if( $tkey == "MP")
                    <option selected value="{{$tkey}}"> {{$tval}}</option>
                    @else
                    <option value="{{$tkey}}"> {{$tval}}</option>
                    @endif
                    @endforeach
                </select>
                @else
                <input type="hidden" id="BUSCADOR-TIPO-STOCK" value="{{$TIPO}}">
                @endif



                <input id="search" type="text" placeholder="BUSCAR POR CÓDIGO DE BARRA O DESCRIPCION" oninput="buscar_items__( this)">


                <table id="BUSCADOR-TABLE" class="table  table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>CÓDIGO</th>
                            <th>DESCRIPCIÓN</th>
                        </tr>
                    </thead>
                    <tbody id="buscador_grill">

                        @foreach( $items as $item)
                        <tr id="{{$item->REGNRO}}" onclick="seleccionar_item_buscador(event)" class="buscador-row">
                            <td class="p-0">{{$item->REGNRO}}</td>
                            <td class="p-0">{{$item->CODIGO}}</td>
                            <td class="p-0">{{$item->DESCRIPCION}}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>


            </div>

        </div>
    </div>
</div>


<style>
    #BUSCADOR-TABLE {
        color: black;
        font-weight: 600;
    }



    tr.buscador-row:hover {
        background-color: #cd7007 !important;
    }

    #search {
        border-radius: 20px;
        color: black;
        text-align: center;
        font-size: 20px;
        font-family: mainfont;
        border: none;
        background: #fdeb6f;
        margin: 5px;
        width: 100%;
        height: 40px;
        border-bottom: 3px white solid !important;
    }

    #search:focus {
        border-radius: 20px;
        border: #cace82 1px solid;
    }

    #search::placeholder {

        font-size: 20px;
        color: black;
        font-family: mainfont;
        text-align: center;
    }

    #BUSCADOR-TIPO-STOCK {
        background: white !important;
        color: black !important;
    }
</style>




<script>
    window.buscador_items_data_model = [];
    window.buscador_items_modelo = {};
    if( !("buscar_items_target" in window))
    window.buscar_items_target = undefined; //Object




    function seleccionar_item_buscador(ev) {

        let elegido = ev.currentTarget.id;

        let modelo = buscador_items_data_model.filter((ar) => ar.REGNRO == elegido);
        if (modelo.length > 0) {
            buscador_items_modelo = modelo[0];

            //Existe target?
            if (window.buscar_items_target != undefined  &&   typeof  window.buscar_items_target == "function") {
                window.buscar_items_target(  buscador_items_modelo );
            }
            //CERRAR
            $(".modal.show").modal("hide");
        }
    }



    async function buscar_items__(esto) {



        let td_const = function(ar) {
            return "<td class='p-0'>" + ar + "</td>";
        };
        let tr_const = function(row) {
            let claves = Object.keys(row);
            let tds = claves.map((cla) => td_const(row[cla]));
            let id_row = row.REGNRO;
            let evento = "onclick='seleccionar_item_buscador(event)' ";

            return "<tr  " + evento + " id='" + id_row + "' class='buscador-row'>" + tds + "</tr>";
        };

        let termino = esto == undefined ? "" : esto.value;
        let tipo_Stock = $("#BUSCADOR-TIPO-STOCK").val();
        //Seleccionar url

        let grill_url = $("#buscador_stock_url").val();

        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#buscador_grill").html(loader);

        /***Request */
        /**Header */
        let header_req = {
            'X-Requested-With': "XMLHttpRequest",
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'formato': 'json'
        };
        /**Si se busca un producto, filtrar solo los de tipo PROD. VENTA */
        let body_req = "buscado=" + termino + "&tipo=" + tipo_Stock;
        // if( modoBusqueda == "P")   body_req= body_req+"&tipo=P";
        /**Sending request */
        let req = await fetch(grill_url, {

            method: "POST",
            headers: header_req,
            body: body_req
        });
        /**Response is coming */
        buscador_items_data_model = await req.json();


        let html__ = buscador_items_data_model.map((ar) => {
            return {
                REGNRO: ar.REGNRO,
                CODIGO: ar.CODIGO,
                DESCRIPCION: ar.DESCRIPCION
            };
        }).map(tr_const);

        $("#buscador_grill").html(html__);
        //modal
        $("#buscador_de_items").modal("show");
    }
</script>
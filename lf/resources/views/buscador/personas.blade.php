<style>
    .BUSCADOR-TABLE {
        color: black;
        font-weight: 600;
    }

    tr.buscador-row:hover {
        background-color: #cd7007 !important;
    }

    .search {
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

@if( $TIPO == "CLIENTE")
<input type="hidden" id="buscador_people_url" value="{{url('clientes/buscar')}}">
@elseif( $TIPO == "PROVEEDOR")
<input type="hidden" id="buscador_people_url" value="{{url('proveedores/buscar')}}">
@endif


<div id="buscador_de_personas" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input class="search" type="text" placeholder="BUSCAR POR NOMBRE, O CÉDULA, O RUC" oninput="buscar_people_( this)">
                <table class="BUSCADOR-TABLE  table  table-striped table-hover">
                    <thead>
                        <tr>
                            <th>CÉDULA</th>
                            <th>NOMBRES</th>
                        </tr>
                    </thead>
                    <tbody>


                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>










<script>
    window.buscador_people_data_model = [];
    window.buscador_people_modelo = {};
    window.buscador_people_target= {};



    async function buscador_de_personas(   target    ) {
        window.buscador_people_target=   target  ;
        await buscar_people_();
        $("#buscador_de_personas").modal("show");

    }





    var seleccionar_persona = function(ev) {

        let elegido = String(   ev.currentTarget.id );

        let modelo = buscador_people_data_model.filter((ar) => String(ar.REGNRO) == elegido);
        if (modelo.length > 0) {
            buscador_people_modelo = modelo[0];
            $(".modal.show").modal("hide");
            //Montar los valores
            $(  window.buscador_people_target.KEY  ).val(  buscador_people_modelo.REGNRO );
            $( window.buscador_people_target.NAME ).val(  buscador_people_modelo.NOMBRE );

        }
    };


    async function buscar_people_(esto) {

        let td_const = function(ar) {
            return "<td class='p-0'>" + ar + "</td>";
        };
        let tr_const = function(row) {
            let claves = Object.keys(row);
            let tds = claves.map((cla) => td_const(row[cla]));
            let id_row = row.REGNRO;
            let evento = "onclick='seleccionar_persona(event)' ";

            return "<tr  " + evento + " id='" + id_row + "' class='buscador-row'>" + tds + "</tr>";
        };

        let termino = esto == undefined ? "" : esto.value;
        //Seleccionar url

        let grill_url = $("#buscador_people_url").val();

        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
        $("#buscador_de_personas table tbody").html(loader);

        /***Request */
        /**Header */
        let header_req = {
            'X-Requested-With': "XMLHttpRequest",
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'formato': 'json'
        };
        /**Si se busca un producto, filtrar solo los de tipo PROD. VENTA */
        let body_req = "buscado=" + termino + "";
        // if( modoBusqueda == "P")   body_req= body_req+"&tipo=P";
        /**Sending request */
        let req = await fetch(grill_url, {

            method: "POST",
            headers: header_req,
            body: body_req
        });
        /**Response is coming */
        buscador_people_data_model = await req.json();


        let html__ = buscador_people_data_model.map((ar) => {
            return {
                REGNRO: ar.REGNRO,
                CEDULA_RUC: ar.CEDULA_RUC,
                NOMBRE: ar.NOMBRE
            };
        }).map(tr_const);

        $("#buscador_de_personas table tbody").html(html__);
    }
</script>
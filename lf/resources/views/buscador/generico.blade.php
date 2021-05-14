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
</style>

<?php

$URL__ = "";
switch ($TIPO) {
    case 'CAJA':
        $URL__ =  url("caja/buscar");
        break;
    case 'TURNO':
        $URL__ = url("caja/buscar");
        break;
}
?>
<input type="hidden" id="buscador_url" value="{{$URL__}}">



<div id="buscador_generico" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input class="search" type="text" placeholder="BUSCAR POR DESCRIPCIÓN" oninput="buscador_registros_( this)">
                <table class="BUSCADOR-TABLE  table  table-striped table-hover">
                    <thead>
                        <tr>
                            <th>NÚMERO</th>
                            <th>DESCRIPCIÓN</th>
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
    var buscadorGenerico = {


        target: () => {
            ;
        },
        buscador_data_model: [],
        buscador_modelo: {},
        tipo_busqueda: '',
        init: async function(target, tipo) {
            this.target = target;
            this.tipo_busqueda=  tipo;
            await this.buscador_registros_();
            $("#buscador_generico").modal("show");

        },

        obtenerUrl(){

            let url__="";
            switch(  this.tipo_busqueda){
                case 'CAJA' : url__= "<?=url('caja/buscar')?>"; break;
                case 'TURNO': url__= "<?=url('turno/buscar')?>";break;
            }
            return url__;
        },
        seleccionar_registro: function(ev) {

            let elegido = String(ev.currentTarget.id);

            let modelo = buscadorGenerico.buscador_data_model.filter((ar) => String(ar.REGNRO) == elegido);
            if (modelo.length > 0) {
                buscadorGenerico.buscador_modelo = modelo[0];
                $(".modal.show").modal("hide");
                //Montar los valores
                if (typeof buscadorGenerico.target == "function")
                    buscadorGenerico.target(buscadorGenerico.buscador_modelo);

            }
        },

        buscador_registros_: async function(esto) {

            
            let td_const = function(ar) {
                return "<td class='p-0'>" + ar + "</td>";
            };
            let tr_const = function(row) {
                let claves = Object.keys(row);
                let tds = claves.map((cla) => td_const(row[cla]));
                let id_row = row.REGNRO;
                let evento = "onclick='buscadorGenerico.seleccionar_registro(event)' ";

                return "<tr  " + evento + " id='" + id_row + "' class='buscador-row'>" + tds + "</tr>";
            };

            let termino = esto == undefined ? "" : esto.value;
            //Seleccionar url

            let grill_url = this.obtenerUrl();

            let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
            $("#buscador_generico table tbody").html(loader);

            /***Request */
            /**Header */
            let header_req = {
                'X-Requested-With': "XMLHttpRequest",
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'formato': 'json'
            };
            /**Si se busca un producto, filtrar solo los de tipo PARA VENTA */
            let body_req = "buscado=" + termino + "";
            // if( modoBusqueda == "P")   body_req= body_req+"&tipo=P";
            /**Sending request */
            let req = await fetch(grill_url, {

                method: "POST",
                headers: header_req,
                body: body_req
            });
            /**Response is coming */
            this.buscador_data_model = await req.json();


            let html__ = this.buscador_data_model.map((ar) => {
                return {
                    REGNRO: ar.REGNRO,
                    DESCRIPCION: ar.DESCRIPCION
                };
            }).map(tr_const);

            $("#buscador_generico table tbody").html(html__);
        }

    }
</script>
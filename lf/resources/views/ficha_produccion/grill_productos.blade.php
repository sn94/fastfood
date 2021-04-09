<div id="MODAL-PRODUCTOS" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Modal body text goes here.</p>
            </div>

        </div>
    </div>
</div>
<fieldset id="PRODUCTOS-PANEL">
    <legend> PRODUCTOS A ELABORAR </legend>

    <div class="container">

        <div class="row  ">

            <div class="col-12 col-md-8 pr-0  mb-1"  style="display: flex;  flex-direction: row;"  >
                <label >ITEM:</label>
                <a  data-toggle="tooltip" title="Click aquí para buscar un ítem"    href="#" onclick="productos_controller.buscador_de_items('PE')"><i class="fa fa-search"></i></a>
                <input style="width:60px; font-weight: 600; color: black; " class="form-control ITEM-ID" type="text" disabled>
                <input disabled  class="form-control ITEM" style="width: 100% !important; height: 40px;" autocomplete="off" type="text"   >
            </div>

            <div class="col-12 col-md-4 pl-1 mb-1" style="display: flex;  flex-direction: row;">
                <label  >CANT.: </label>
                <input onkeydown="if(event.keyCode==13) {event.preventDefault(); productos_controller.cargar_tabla();}"   class="form-control decimal CANTIDAD" type="text" />
                <label class="MEDIDA" ></label>
                <a   href="#" onclick="productos_controller.cargar_tabla()"><i class="fa fa-download"></i></a>
            </div>
        </div>

        <div class="row  mt-0 pt-1 pb-2 pr-2 pl-2 pr-md-2 pl-md-2">
            <div class="col-12  p-0">
                <table class="table table-striped table-secondary text-dark PRODUCCION">

                    <thead>
                        <tr style="font-size: 14px;">
                        <th>CÓDIGO</th>
                            <th>DESCRIPCIÓN</th>
                            <th>UNI.MED.</th>
                            <th> CANTIDAD</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="DETALLE">

                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="col-12 col-md-6 p-0">
            </div>
        </div>
    </div>
</fieldset>



<script>
    var productos_model = [];
    var productos_controller = {};


    var init_productos = function() {

        //INIT
        productos_controller = deepClone(itemSearcherObject);
        //Nombre del panel
        productos_controller.panel_name = "#PRODUCTOS-PANEL";
        //modal
        productos_controller.modal_name = "#MODAL-PRODUCTOS";
        productos_controller.name = "products";

        let panel = productos_controller.panel_name;
        
        productos_controller.callbackPostModal= function( seleccionado){
            let regnro = seleccionado.REGNRO;
            let descri = seleccionado.DESCRIPCION;
            let medida = seleccionado.unidad_medida.DESCRIPCION;
            productos_controller.buscador_items_modelo = seleccionado;
            window.buscador_items_modelo= seleccionado;

            $(panel + " .ITEM-ID").val(regnro);
            $(panel + " .ITEM").val(descri);
            $(panel + " .MEDIDA").text(medida);

           // $(productos_controller.modal_name + " .modal-body").html("");

        };

        
        /*
        Backup 
        $(productos_controller.modal_name).on('hidden.bs.modal', function(e) {

            let regnro = buscador_items_modelo.REGNRO;
            let descri = buscador_items_modelo.DESCRIPCION;
            let medida = buscador_items_modelo.MEDIDA;
            productos_controller.buscador_items_modelo = buscador_items_modelo;

            $(panel + " .ITEM-ID").val(regnro);
            $(panel + " .ITEM").val(descri);
            $(panel + " .MEDIDA").text(medida);

            $(productos_controller.modal_name + " .modal-body").html("");

            //cargar_tabla();
        });*/

    };
</script>
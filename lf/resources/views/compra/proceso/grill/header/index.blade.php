


<div class="row">
    <div class="col-12 col-md-6 col-lg-5 " >


        <div style="display: flex; flex-direction: row;">
            <label class="pr-1 ">PRODUCTO:</label>
            <a href="#" onclick="onCreateStock()"><i class="fa fa-plus"></i></a>
            <a href="#" onclick="buscarItemParaCompra()"><i class="fa fa-search"></i></a>
        </div>

        <div style="display: flex; flex-direction: row;">
        <input style="width: 100px;   font-weight: 600; color: black;" class="form-control form-control-sm" type="text" id="COMPRA-ITEM-ID" disabled>
        <input disabled class="form-control form-control-sm " autocomplete="off" type="text" id="COMPRA-ITEM-DESC">
        </div>

    </div>




    <div class="col-12  col-sm-4 col-md-2 col-lg-2  ">

        <label style="width: 200px !important;" class="pr-1 ">PRECIO UNIT.: </label>
        <input onfocus="if(this.value=='0') this.value='';"  onblur="if(this.value=='') this.value= '0';" onkeydown="if(event.keyCode==13) {event.preventDefault(); compraObj.cargar_tabla();}" id="COMPRA-PRECIO" class="form-control form-control-sm entero text-end" type="text" />


    </div>

    <div class="col-12   col-sm-4 col-md-2 col-lg-1">
        <label>IVA:</label>
        <select id="COMPRA-IVA" class="form-control form-control-sm ">
            <option value="10">10%</option>
            <option value="5">5%</option>
        </select>
    </div>


    <div class="col-12 col-sm-4  col-md-2 col-lg-4 mb-1">
        <div style="display: flex; flex-direction: row;">

            <div style="display: flex; flex-direction: column;">
                <label>CANTIDAD: </label>
                <div style="display: flex; flex-direction: column;">
                    <input onkeydown="if(event.keyCode==13) {event.preventDefault(); compraObj.cargar_tabla();}" id="COMPRA-CANTIDAD" class="form-control form-control-sm decimal" type="text" />
                    <label class="MEDIDA" id="COMPRA-MEDIDA"></label>
                </div>
            </div>


            <a style="display: flex; flex-direction: row; align-items: flex-end;" href="#" onclick="compraObj.cargar_tabla()"><i class="fa fa-download"></i></a>
        </div>
    </div>


    <input type="hidden" id="COMPRA-TIPO">

</div>

@include("compra.proceso.grill.header.js")
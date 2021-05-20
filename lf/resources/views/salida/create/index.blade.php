@extends("templates.admin.index")


@section("content")

@php
use App\Models\Sucursal;

//FUENTES
$SUCURSALES= Sucursal::get();

$urlAction=  isset($SALIDA) ? url('salida/update')  : url('salida/create');

@endphp




@include("salida.estilos")
@include("salida.routes")
@include("buscador.Buscador")

<div class="container-fluid col-12 col-sm-11 col-md-10 col-lg-7 bg-dark text-light mt-2 pb-5 px-5">

    <h2 class="text-center mt-2">Salidas de productos y materia prima</h2>

    <div id="loaderplace"></div>

    <form id="SALIDAFORM" action="<?= $urlAction ?>" method="POST" onkeypress="if(event.keyCode == 13) event.preventDefault();" onsubmit="guardarSalida(event)">
        <div class="row ">
            <div class="col-12 col-md-2  mb-1">
                <button type="submit" class="btn btn-danger"> GUARDAR</button>
            </div>
        </div>

        @include("salida.create.header")
        @include("salida.create.grill")
    </form>

</div>


@include("validations.form_validate")
@include("validations.formato_numerico")

<script>
    /**Cargar a la tabla  */




    function buscarItem() {
        let TIPOITEM = $("select[name=TIPO_SALIDA]").val();
        
        Buscador.url = "<?= url("stock/buscar") ?>/" + TIPOITEM;
        Buscador.htmlFormForParams = `<form> <input name='TIPO' type='hidden' value='${TIPOITEM}'> </form>`;

        Buscador.htmlFormFieldNames = ['TIPO'];
        Buscador.columnNames = ["REGNRO", "DESCRIPCION"];
        Buscador.columnLabels = ['ID', 'DESCRIPCION'];
        Buscador.callback = function(respuesta) {
           
            const {
                REGNRO,
                DESCRIPCION,
                unidad_medida
            } = respuesta;
            window.buscador_items_modelo = respuesta;
            $("#SALIDA-ITEM-ID").val(REGNRO);
            $("#SALIDA-ITEM-DESC").val(DESCRIPCION);
            $("#SALIDA-MEDIDA").text(unidad_medida.UNIDAD);
        };
        Buscador.render();
    }


    async function guardarSalida(ev) {
        //config_.processData= false; config_.contentType= false;
        ev.preventDefault();


        formValidator.init(ev.target);
        if ($("#SALIDA-DETALLE").children().length == 0) {
            alert("Cargue al menos un item");
            return;
        }
        show_loader();
        //componer
        let cabecera = formValidator.getData("application/json");
        let detalle = salida_model;
        let req = await fetch(ev.target.action, {
            "method": "POST",

            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                CABECERA: cabecera,
                DETALLE: detalle
            })
        });
        let resp = await req.json();
        hide_loader();
        if ("ok" in resp) {

            formValidator.limpiarCampos();
            $("input[name=FECHA]").val((new Date()).getFecha());
            limpiar_tabla();
            alert(resp.ok);
            window.location = "<?= url('salida/index') ?>";
        } else {
            alert(resp.err);
        }


    }



    async function restaurar_modelo_salida() {
        //item cantidad tipo medida
        let row = document.querySelectorAll("#SALIDA-TABLE tbody tr");
        if (row.length == 0) return;

        let modelo = Array.prototype.map.call(row, function(domtr) {

            let ITEM = domtr.id;
            let CODIGO = domtr.children[0].textContent;
            let CANTIDAD = domtr.children[3].textContent;
            let MEDIDA = domtr.children[2].textContent;
            let TIPO = domtr.className.split("-")[0];
            return {
                CODIGO: CODIGO,
                ITEM: ITEM,
                CANTIDAD: CANTIDAD,
                MEDIDA: MEDIDA,
                TIPO: TIPO
            };
        });
        salida_model = modelo;
    }


    async function buscarFichaProduccion() {
  
  Buscador.url = "<?= url("ficha-produccion/index") ?>" ;
  Buscador.httpMethod = "post" ;
  Buscador.mostrarCampoBusquedaPorPatron= false;
  Buscador.reiniciarRequestAlFiltrar = true;
  Buscador.htmlFormForParams = `<form> <label class='fw-bold text-dark fs-6'>Por Fecha:</label> <input name='FECHA' type='date' onchange='Buscador.filtrar()' > </form>`;
  
  Buscador.htmlFormFieldNames = ['FECHA'];
  Buscador.columnNames = ["REGNRO", "ELABORADO_POR"];
  Buscador.columnLabels = ['ID', 'REGISTRADO POR'];
  Buscador.callback = function(respuesta) {
  
      const {
          REGNRO,
          ELABORADO_POR
      } = respuesta;
  
      window.buscador_items_modelo = respuesta;
      console.log( respuesta);
     
      $("#PRODUCCION_ID").val(REGNRO);
     /* $(PANEL_ID + " .ITEM").val(DESCRIPCION);
      $(PANEL_ID + " .MEDIDA").text(unidad_medida.UNIDAD);*/
  };
  Buscador.render();
  }


  
    window.onload = async function() {
        //   autocompletado_items();
        restaurar_modelo_salida();
        formatoNumerico.formatearCamposNumericosDecimales("SALIDAFORM");


    };
</script>

@endsection
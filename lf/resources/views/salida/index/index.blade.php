 @extends( "templates.admin.index")

 @section("PageTitle")
 Salidas de Productos my materia prima
 @endsection


 @section("content")



 <style>
     #search {
         height: 25px !important;
         padding: 1px !important;
         margin: 0px !important;
         background-color: white !important;
         width: 100%;
     }

     #search:focus {
         border-radius: 20px;
         border: #cace82 1px solid;
     }

     #search::placeholder {

         font-size: 14px;
         font-weight: 600;
         color: black;
         font-family: mainfont;
         text-align: center;
     }
 </style>

 <input type="hidden" id="GRILL-URL" value="{{url('clientes')}}">
 <input type="hidden" id="GRILL-URL-CUSTOM" value="{{url('clientes/buscar')}}">






 <div class="container-fluid col-12 col-md-12 col-lg-10 col-xl-8">
     <h2 class="text-center mt-2" style="font-family: titlefont;">Salidas de productos y materia prima</h2>

     <div id="loaderplace"></div>

     <a class="btn btn-warning mb-1" href="{{url('deposito/salida')}}">NUEVA SALIDA</a>

     @include("salida.search_params")
     <div class="mt-2" id="grill" style="min-height: 300px;">

     </div>
 </div>


 <script>
     function show_loader() {
         let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
         $("#loaderplace").html(loader);
     }

     function hide_loader() {
         $("#loaderplace").html("");
     }





     class buscadorClientes {

         constructor() {
             this.formato = "html"; /** pdf  json  html  */



             this.dataLink = "#GRILL-URL-CUSTOM";

             this.outputTarget = "#grill";

             this.parametros = {},

                 this.formatoHtml = this.formatoHtml.bind(this);
             this.formatoExcel = this.formatoExcel.bind(this);
             this.formatoPdf = this.formatoPdf.bind(this);
             this.buscar = this.buscar.bind(this);
         }



         setParams() {
             let buscado = $("#search").val();
             let ciudad_id = $("select[name=CIUDAD]").val();

             this.parametros = {
                 buscado: buscado,
                 CIUDAD: ciudad_id
             };
         }

         async formatoHtml() {
             this.formato = "html";
             let req = await this.buscar();
             let resp = await req.text();
             $(this.outputTarget).html(resp);
         }


         async formatoPdf(ev) {
             if (ev != undefined) ev.preventDefault();
             this.formato = "pdf";
             let req = await this.buscar();
             let respBlob = await req.blob();
             var file = window.URL.createObjectURL(respBlob);
             let newWindow = window.open("", "_blank");
             newWindow.location.assign(file);

         }

         async formatoExcel(ev) {
             if (ev != undefined) ev.preventDefault();
             this.formato = "json";
             let req = await this.buscar();
             let resp = await req.json();
             callToXlsGen_with_data("Clientes", resp);

         }
         async buscar() {
             this.setParams();
             let grill_url = $(this.dataLink).val();

             if (this.formato == "html") {
                 show_loader();
                 //   $("#grill").html(loader);
             }
             //Formato de datos seleccionado
             let formato = this.formato;
             //parametros
             let params = Object.entries(this.parametros).map(([clave, valor]) => clave + "=" + valor).join("&");
             let req = await fetch(grill_url, {

                 method: "POST",
                 headers: {
                     'X-Requested-With': "XMLHttpRequest",
                     'Content-Type': 'application/x-www-form-urlencoded',
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                     'formato': formato
                 },
                 body: params
             });

             hide_loader();
             return req;
         }
     };



     async function fill_grill(url_optional) {


         let grill_url = $("#GRILL-URL").val();
         if (url_optional != undefined) {
             url_optional.preventDefault();
             grill_url = url_optional.currentTarget.href;
         }


         let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= url("assets/images/loader.gif") ?>'   />";
         $("#grill").html(loader);
         let req = await fetch(grill_url, {

             headers: {
                 'X-Requested-With': "XMLHttpRequest",
                 Pragma: "no-cache",
                 "Cache-Control": "max-age=0"
             }
         });
         let resp = await req.text();
         $("#grill").html(resp);


     }






     function buscarClientes() {
         //PARMETROS
         let buscado = $("#search").val();
         let ciudad_id = $("select[name=CIUDAD]").val();
         let parametros = {
             buscado: buscado,
             CIUDAD: ciudad_id
         };

         dataSearcher.setDataLink = "#GRILL-URL-CUSTOM";
         dataSearcher.setOutputTarget = "#grill";
         dataSearcher.setParametros = parametros;
         dataSearcher.formatoHtml();
     }
 </script>
 @stop



 @section("jsScripts")


 <script src="<?= url("assets/xls_gen/xls.js") ?>"></script>
 <script src="<?= url("assets/xls_gen/xls_ini.js?v=" . rand(0.0, 100)) ?>"></script>
 <script src="<?= url("assets/js/buscador.js?v=" . rand(0.0, 100)) ?>"></script>



 <script>
     var dataSearcher = undefined;

     window.onload = function() {


         dataSearcher = new DataSearcher();
         fill_grill();
     }
 </script>
 @endsection
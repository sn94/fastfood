 <script>
     window.Buscador = {

         url: "<?= url("clientes") ?>",
         reiniciarRequestAlFiltrar: false,
         mostrarCampoBusquedaPorPatron: true,
         httpMethod: "get",
         httpHeaders: {},
         columnNames: [],
         columnLabels: [],
         dataSource: [],
         dataSelected: undefined,

         callback: undefined,

         tableStyleClasses: "table table-striped",

         htmlFormForParams: undefined,
         htmlFormFieldNames: [], //Deben coincidir con alguno de columnNames

         render: function() {

             //Crear estilos
             var hoja = document.createElement('style')
             hoja.id = "BuscadorStyles";
             hoja.innerHTML = this.styles();
             document.head.appendChild(hoja);

             $("body").prepend(this.modalHtml());
             /* document.querySelector("html").innerHTML = 
              this.modalHtml.concat(document.querySelector("html").innerHTML);*/
             //Mostrar formulario de parametros si esta definido
             if (this.htmlFormForParams != undefined) {
                 $("#BuscadorModal .form").prepend(this.htmlFormForParams);
             }
             this.consultar();
         },



         customHandler: undefined,

         cerrarModal: function() {
             document.querySelector("#BuscadorModal").remove();
             document.querySelector("#BuscadorStyles").remove();
             if (this.customHandler != undefined && typeof this.customHandler == "function")
                 this.customHandler();
             this.customHandler = undefined;
             this.htmlFormForParams = undefined;
             //Desmontar componente
             this.mostrarCampoBusquedaPorPatron= true;
             this.reiniciarRequestAlFiltrar= false;
         },
         modalHtml: function(){
             return `
    <div id="BuscadorModal" class="container-fluid p-1 p-md-5 m-0">

    <div class="BuscadorModal-cuerpo container  col-12 col-md-5">
        <div class="BuscadorModal-close">
            <button onclick="Buscador.cerrarModal()" type="button"  >X</button>
        </div>
        
        <div class="form"></div> 

       ${ this.mostrarCampoBusquedaPorPatron ?  `<input class="buscador-input-search" type="text" placeholder="BUSCAR POR DESCRIPCIÓN" oninput="Buscador.filtrar( this)">` : '' }
       
        <div class="container-fluid content m-0" style="background: white;">
            <p>Cargando...</p>
        </div>
    </div>
   
    </div>
            `;
         },
         removeClass: function(selector, clase) {
             Array.prototype.filter.call(
                 document.querySelector(selector).classList,
                 function(ae) {
                     console.log(ae);
                     return ae != clase
                 }
             );

         },
         addClass: function(selector, clase) {
             document.querySelector(selector).classList.add(clase);
         },

         tipoFondo: "translucido", //translucido - opaco - ninguno
         styles: function() {
             return `
            #BuscadorModal{
                position: absolute;
                z-index: 1000;
                background-color: ${this.tipoFondo == "translucido" ? "#000000f5"  : (  this.tipoFondo == "opaco" ? "#000000" :  "transparent" ) };
                height: 100%;width: 100%;
            }
            .BuscadorModal-cuerpo{
                border-radius: 8px;
                background-color: var(--color-1);
            }
            #BuscadorModal .content table tbody tr:hover {
                background-color: #fdeb6f !important;
            } 

            .BuscadorModal-close button{
                border-radius: 50px;
                background-color: black;
                color: white;
                padding: 2px 10px;
                transition: all 1s easy;
            }
            .BuscadorModal-close button:hover{
                border: 1px solid black; 
                font-weight: bold;
                box-shadow: 0 0 10px black;
            }
            .buscador-input-search {
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
            #buscador-input-search:focus {
            border-radius: 20px;
            border: #cace82 1px solid;
        }
            #buscador-input-search::placeholder {

                font-size: 20px;
                color: black;
                font-family: mainfont;
                text-align: center;
            }

            #BuscadorModal table thead th, #BuscadorModal table tbody td{
                padding: 0px;
            }
            #BuscadorModal table thead th:nth-child(1), #BuscadorModal table tbody td:nth-child(1){
                width: 80px;
            }

            
                `;
         },
         makeTable: function(data) {

             if (data == undefined && !(Array.isArray(data))) return;
           //  if (data.length == 0) return;

             //Keys de columnas a renderizar 
             let columnasRendr = (this.columnNames.length == 0) ? Object.keys(data[0]) : this.columnNames;


             /**labels */
             let columnas = this.columnLabels.length == 0 ? Object.keys(data[0]) : this.columnLabels;

             let labels = "";
             labels = columnas.map(ar => {
                 //  if (columnasRendr.includes(ar))
                 return "<th>" + ar + "</th>";
                 //  else return " ";
             }).join("");

             let header = `
                <thead>
                <tr>${ labels   }</tr>
                </thead>
                `;

             let trConst = (row) => {

                 let id_row = row.REGNRO;

                 let cols = Object.entries(row).map(
                     ([K, V]) => {
                         if (columnasRendr.includes(K))
                             return `<td>${V}</td>`;
                         else return "";
                     }
                 );
                 return `<tr id='${id_row}' onclick='Buscador.seleccionar_registro(event)' > ${cols} </tr>`;
             };
             let body = `<tbody>${ data.map(trConst).join("") }</tbody>`;

             let tableTag = document.createElement("table");
             $(tableTag).addClass(this.tableStyleClasses);
             $(tableTag).html(` 
                ${header}
                ${body}
                
                `);
                let tableContainerTag = document.createElement("div");
                tableContainerTag.style.height= "250px";
                tableContainerTag.style.overflow= "scroll";
                tableContainerTag.appendChild(  tableTag);
             //  tableTag.innerHTML =  `    ${header}  ${body}   `;
             $("#BuscadorModal .content").html("");

             let html = tableTag;
             document.querySelector("#BuscadorModal .content table")?.remove(); //limpiar

             document.querySelector("#BuscadorModal .content").appendChild( tableContainerTag);

         },

         consultar: async function() {

             /**Determinar filtrado */
             let postBody = {};
             if (Buscador.htmlFormFieldNames != undefined && Buscador.htmlFormFieldNames.length > 0) {
                 Buscador.htmlFormFieldNames.forEach(
                     (arg) => {
                         let paramX = $("#BuscadorModal form [name=" + arg + "]").val();
                         postBody[arg] = paramX;
                     }
                 );
                  
             }


             //Request 
             let request_header = {
                 'X-Requested-With': 'XMLHttpRequest',
                 'formato': 'json'
             };
             Object.assign(request_header, this.httpHeaders);

             let request_setting = {
                 method: this.httpMethod,
                 headers: request_header
             };
             if (this.httpMethod.toLowerCase() == "post") {
                 request_setting.headers['Content-Type'] = "application/json";
                 request_setting.body = JSON.stringify(postBody);
             }
             let req = await fetch(this.url, request_setting);

             let resp = await req.json();

             this.dataSource = resp;
             console.log( "Data source" ,  resp );

            // if (resp.length == 0) return;

             this.makeTable(this.dataSource);
         },

         filtrar: async function(input) {

            if( this.reiniciarRequestAlFiltrar )
            await this.consultar();

             /* if (input == undefined) {
                  let filtradoPorParametros = Buscador.dataSource.slice();
                  Buscador.htmlFormFieldNames.forEach(
                      (arg) => {
                          let paramX = $("#BuscadorModal form [name=" + arg + "]").val();
                          console.log("Parm aprma", paramX);
                          filtradoPorParametros = filtradoPorParametros.filter(ar => ar[arg] == paramX);
                      }
                  );

                  this.makeTable(filtradoPorParametros);
                  return;
              }*/


             if (input != undefined && input.value != "") {

                 let filtrado = Buscador.dataSource.filter(arg => {
                     let entrada = input.value;
                     let crearExpr = function(word) {

                         return new RegExp(".*" + word + ".*", "i");
                     };
                     //Tomar dos campos para filtrar 
                     let claves = Object.keys(arg);
                     console.log("Tarrget", entrada, " Claves: ", claves);
                     let filtroResult = claves.reduce((ini, val) => {
                         console.log( "Bool ini",  ini, entrada, " compare ", arg[val]  );
                         return (ini || crearExpr(entrada).test(arg[val]));
                     }, false);
                     return filtroResult;
                     /* return (crearExpr(entrada).test(arg.CEDULA_RUC) ||
                          crearExpr(entrada).test(arg.NOMBRE ));*/

                 });
                 Buscador.makeTable(filtrado);
             } else Buscador.makeTable(Buscador.dataSource);

             if (input != undefined)
                 input.value = input.value;
         },

         seleccionar_registro: function(ev) {

             let elegido = String(ev.currentTarget.id);
             let modelo = Buscador.dataSource.filter((ar) => String(ar.REGNRO) == elegido);
             if (modelo.length > 0) {
                 Buscador.dataSelected = modelo[0];
                 Buscador.cerrarModal();
                 if (Buscador.callback != undefined && typeof(Buscador.callback) == "function")
                     Buscador.callback(Buscador.dataSelected);
             }
         }





     };
 </script>
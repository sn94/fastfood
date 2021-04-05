 



    <script>
        window.Buscador = {

            url: "<?= url("clientes") ?>",

            columnNames: [],
            columnLabels: [],

            dataSource: [],
            dataSelected: undefined,

            callback: undefined,

            render: function() {

                //Crear estilos
                var hoja = document.createElement('style')
                hoja.id = "BuscadorStyles";
                hoja.innerHTML = this.styles;
                document.body.appendChild(hoja);

                document.querySelector("html").innerHTML = document.querySelector("html").innerHTML.concat(this.modalHtml);
                this.consulta();
            },



            customHandler: undefined,

            cerrarModal: function() {
                document.querySelector("#BuscadorModal").remove();
                document.querySelector("#BuscadorStyles").remove();
                if (this.customHandler != undefined && typeof this.customHandler == "function")
                    this.customHandler();
                this.customHandler = undefined;
                //Desmontar componente
            },
            modalHtml: `
    <div id="BuscadorModal" class="container-fluid p-1 p-md-5 m-0">

    <div class="BuscadorModal-cuerpo container col-12 col-md-4">
        <div class="BuscadorModal-close">
            <button onclick="Buscador.cerrarModal()" type="button" style=" border-radius: 20px;background-color: red;color: white;padding: 5 px;" >X</button>
        </div>
        <input class="buscador-input-search" type="text" placeholder="BUSCAR POR DESCRIPCIÓN" oninput="Buscador.filtrar( this)">
        <div class="container-fluid content m-0" style="background: white;"></div>
    </div>
   
    </div>
            `,
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

            styles: `
            #BuscadorModal{
                position: absolute;
                z-index: 1000;
                background-color: #000000f5;
                height: 100%;width: 100%;
            }
            .BuscadorModal-cuerpo{
                width: 350px;
                margin:auto;
            }
            #BuscadorModal .content table tbody tr:hover {
                background-color: #cd7007 !important;
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

                `,
            makeTable: function(data) {

                if (data == undefined && !(Array.isArray(data))) return;
                if (data.length == 0) return;

                //Keys de columnas a renderizar 
                let columnasRendr = (this.columnNames.length == 0) ? Object.keys(data[0]) : this.columnNames;


                /**labels */
                let columnas = this.columnLabels.length == 0 ? Object.keys(data[0]) : this.columnLabels;
                let labels = "";
                labels = columnas.map(ar => {
                    if (columnasRendr.includes(ar))
                        return "<th>" + ar + "</th>";
                    else return "";
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

                let html = `
                <table>
                ${header}
                ${body}
                </table>
                `;

                document.querySelector("#BuscadorModal .content").innerHTML = html;
            },

            consulta: async function() {

                let req = await fetch(this.url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'formato': 'json'
                    }
                });
                let resp = await req.json();

                this.dataSource = resp;

                if (resp.length == 0) return;

                this.makeTable(this.dataSource);
            },
            filtrar: function(input) {

                if (input.value) {
                    let filtrado = Buscador.dataSource.filter(arg => {
                        let entrada = input.value;
                        let crearExpr = function(word) {

                            return new RegExp(".*" + word + ".*", "i");
                        };
                        return (crearExpr(entrada).test(arg.CEDULA_RUC) ||
                            crearExpr(entrada).test(arg.NOMBRE));

                    });
                    Buscador.makeTable(filtrado);
                } else Buscador.makeTable(Buscador.dataSource);

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
 
<script>
    var printDocument = {

        numeroDeCopias: -1,
        numeroDeCopiasRestante: -1,

        colaVentanas: [],

        printHtml: function(html) {
            this.print(html);
        },
        printFromUrl: async function(url) {



            let params = await fetch("<?= url('parametros/index') ?>", {
                headers: {
                    formato: "json"
                }
            });
            let parametros = await params.json();


            this.numeroDeCopias = parseInt(parametros.NRO_COPIAS);
            this.numeroDeCopiasRestante = this.numeroDeCopias;

            let nrocopias = this.numeroDeCopias;
            let nrocopias_r = this.numeroDeCopias;

            while (nrocopias_r > 0) {
                console.log("Total copias", nrocopias, "Restante", this.numeroDeCopiasRestante)
                let req = await fetch(url, {
                    headers: {
                        "total-copias": nrocopias,
                        "total-copias-r": nrocopias_r
                    }
                });
                let resp = await req.text();
                this.print(resp);


                this.numeroDeCopiasRestante--;
                nrocopias_r = this.numeroDeCopiasRestante;
            }
            this.numeroDeCopias= -1;
           
            //imprimir cada una
            this.colaVentanas.forEach(element => {

                element.print();
               // element.close();
            });
            this.colaVentanas= [];
        },
        print: function(html) {
            let documentTitle = "Ticket";
console.log("Mostrar ventana")
            let ventana = window.open("", "_blank"); //'_blank'

            // 'height=400,width=600,resizable=no' 
            //  ventana.document.write("<style> @page   {  size:  auto;   margin: 0mm;  margin-left:10mm; }</style>");

            ventana.document.write(html);
            ventana.document.close();
            this.colaVentanas.push(ventana);
            // ventana.focus();
            // ventana.print();
            // ventana.close();
            // return true;



        }

    };
</script>
<script>
    var printDocument = {

        numeroDeCopias: -1,
        numeroDeCopiasRestante: -1,

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
                        formato: "blabla",
                        "total-copias": nrocopias,
                        "total-copias-r": nrocopias_r
                    }
                });
                let resp = await req.text();
                this.print(resp);
                this.numeroDeCopiasRestante--;
                nrocopias_r = this.numeroDeCopiasRestante;
            }
        },
        print: function(html) {
            let documentTitle = "Ticket";

            if ("ventana" in window) {
                if (window.ventana) {
                    if (!window.ventana.closed) {
                        window.ventana.close();
                        window.ventana = window.open("", 'PRINT', 'height=300,width=600,resizable=no');
                        console.log("ventana se ha cerrado, abierta otra insta")
                    } else {
                        delete window.ventana;
                        window.ventana = window.open("", 'PRINT', 'height=300,width=600,resizable=no');
                        console.log("ventana cerrada. Se recrea ventana", window.ventana);
                    }
                } else
                window.ventana = window.open("", 'PRINT', 'height=300,width=600,resizable=no');

            } else {

                window.ventana = window.open("", 'PRINT', 'height=300,width=600,resizable=no');
                console.log("Sin instancia. Se crea ventana")
            }

            //'height=400,width=600,resizable=no'
            //  ventana.document.write("<style> @page   {  size:  auto;   margin: 0mm;  margin-left:10mm; }</style>");

            window.ventana.document.write(html);
            window.ventana.document.close();
            window.ventana.focus();
            window.ventana.print();
            window.ventana.close();
            return true;
        }

    };
</script>
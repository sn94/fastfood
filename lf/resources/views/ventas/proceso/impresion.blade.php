<script>
    var printDocument = {

        printHtml: function(html) {
            this.print(html);
        },
        printFromUrl: async function(url) {
            let req = await fetch(url);
            let resp = await req.text();
            this.print(  resp );
        },
        print: function(html) {
            let documentTitle = "LIQUIDACIONES";
            var ventana = window.open("", 'PRINT', 'height=400,width=600,resizable=no');
            ventana.document.write("<style> @page   {  size:  auto;   margin: 0mm;  margin-left:10mm; }</style>");
            ventana.document.write(html);
            ventana.document.close();
            ventana.focus();
            ventana.print();
            ventana.close();
            return true;
        }

    };
</script>
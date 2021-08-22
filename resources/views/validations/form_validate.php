<script>
    var formValidator = {

        form: null,
        init: function(formObject) {
            this.form = formObject;
        },
        limpiarCampos: function() {

            let elements = this.form.elements;
            Array.prototype.forEach.call(elements, function(ar) {
                if (ar.tagName.toLowerCase() == "select")
                    ar.selectedIndex = 0;
                else
                    ar.value = "";
            });
        },
        esNumerico: function(objDom) {
            let clases = objDom.classList;

            let numerico = false;
            for (let g = 0; g < clases.length; g++) {
                if (clases[g] == "entero" || clases[g] == "decimal") {
                    numerico = true;
                    break;
                }
            }
            return numerico;
        },
        limpiarNumero: function(val) {
            let stringified = typeof val == "string" ? val : String(val);
            stringified = stringified == "" ? "0" : stringified;
            let valor_purifi = stringified.replaceAll(new RegExp(/\.*/g), "").replaceAll(new RegExp(/,+/g), ".");

            return valor_purifi;

        },
        getData: function(type) {

            type = type == undefined ? 'application/x-www-form-urlencoded' : type;


            let procesador = function(ar) {


                let valorPurificado = null;
                if (this.esNumerico(ar)) {
                    valorPurificado = this.limpiarNumero(ar.value);
                } else {
                    if (ar.type == "radio") {
                        if (ar.checked)
                            valorPurificado = ar.value;
                        else return;
                    } else
                        valorPurificado = ar.value;
                }
                let nombre = ar.name;
                let valor = valorPurificado;
                return {
                    name: nombre,
                    value: valor
                };

                //list_params.push(ar.name + "=" + valorPurificado);
            };

            let elements = this.form.elements;
            let list_params = Array.prototype.map.call(elements, procesador.bind(this)).filter( ar=> ar!=undefined);

            if (type == "application/x-www-form-urlencoded") { 
                let xwwwformParams = list_params.filter( ar=> "name" in ar ).map((ar) => {
                    return ar.name + "=" + ar.value;
                });
                return xwwwformParams.join("&");

            }
            if (type == "application/json") {
                let object_params = {};  
                let objectParams = list_params.forEach((ar) => {
                    object_params[ar.name] = ar.value;
                }); 
                return object_params;
            }


        }
    };
</script>
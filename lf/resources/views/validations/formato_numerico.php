<script>
  var formatoNumerico = {


    numeroDeDecimales: 3,

    parsearInt: function(arg) {
      try {
        return parseInt(arg);
      } catch (err) {
        return 0;
      }
    },
    parsearFloat: function(arg) {
      try {
        return parseFloat(arg);
      } catch (err) {
        return 0.0;
      }
    },
    darFormatoEnMillares: function(val_float, decimales) {
      let decimales__ = decimales == undefined ? this.numeroDeDecimales : decimales;
      let valor = val_float;

      //FLOAT
      if (decimales__ > 0) {
        try {
          valor = parseFloat(val_float);
        } catch (err) {
          valor = this.limpiarNumeroParaFloat(val_float);
        }
      }

      return new Intl.NumberFormat("de-DE", {
        minimumFractionDigits: decimales__,
        maximumFractionDigits: decimales__
      }).format(valor);
    },
    limpiarNumeroParaFloat: function(val) {
      let conv = String(val);
      return conv.replace(new RegExp(/\.*/g), "").replace(new RegExp(/[,]{1}/g), ".");
    },
    formatearEntero: function(ev) {
      if (ev.data != undefined) {
        if (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) {
          ev.target.value =
            ev.target.value.substr(0, ev.target.selectionStart - 1) +
            ev.target.value.substr(ev.target.selectionStart);
        }
      }
      //Formato de millares
      let val_Act = ev.target.value;
      val_Act = val_Act.replaceAll(new RegExp(/[\.]*[,]*/g), "");
      let enpuntos = new Intl.NumberFormat("de-DE").format(val_Act);
      $(ev.target).val(enpuntos);
    },

    formatearDecimal: function(ev) { //

      let numeroDecimalesPermitidos = 3;

      let valorDecimal = 0;
      if (ev.target.value == "") valorDecimal = 0;
      else {
        if ((ev.target.value.split(",")[1]) == undefined) valorDecimal = 0;
        else {
          if ((ev.target.value.split(",")[1]) == "") valorDecimal = 0;
          else valorDecimal = (ev.target.value.split(",")[1]).length;
        }
      }


      //Eliminar un decimal si es mas de 3 dec.
      if (parseInt(valorDecimal) > numeroDecimalesPermitidos) {
        let normalizado = ev.target.value.substr(0, (ev.target.value.length - 1));
        ev.target.value = normalizado;

      }

      //Eliminar caracteres alfabeticos,

      let normalizado2 = ev.target.value.replace(new RegExp(/[^0-9,]/, "g"), "");
      ev.target.value = normalizado2;
      //Eliminar comas redundantes
      if (/(\d*,\d*,+)/.test(ev.target.value)) {
        let ultimaComaPosicion = ev.target.value.split("").lastIndexOf(",");
        let normalizado3 = ev.target.value.split("").filter((ar, index) => index != ultimaComaPosicion).join("");
        ev.target.value = normalizado3;
      }
      return;  
    },
    limpiarNumeros: function() {
      let nro_campos_a_limp = $(".decimal,.entero").length;

      for (let ind = 0; ind < nro_campos_a_limp; ind++) {
        let valor = $(".decimal,.entero")[ind].value;
        let valor_purifi = valor.replaceAll(new RegExp(/[.]*/g), "").replaceAll(new RegExp(/,+/g), ".");
        $(".decimal,.entero")[ind].value = valor_purifi;
      }
      //return val.replaceAll(new RegExp(/[.]*/g), "");
    },

    restaurarMillares: function() {
      let nro_campos_a_limp = $(".decimal,.entero").length;

      for (let ind = 0; ind < nro_campos_a_limp; ind++) {
        let valor = $(".decimal,.entero")[ind].value;
        //Es un numero decimal?
        //  if( /\./.test(  valor ) )  valor=  valor.replaceAll(".", ",");

        let valor_forma = this.darFormatoEnMillares(valor);
        $(".decimal,.entero")[ind].value = valor_forma;
      }
      //return val.replaceAll(new RegExp(/[.]*/g), "");
    },


    formatearCamposNumericosDecimales: function(FormId) {
      let contexto = this;

      let selector_=  undefined ==  FormId  ?  "form " :  "#" + FormId ; 
      let enteros = document.querySelectorAll(  selector_+ " .entero");
      Array.prototype.forEach.call(enteros, function(inpu) {


        window.handlerPrimero = inpu.oninput;
        let nuevoHandler = function(ev) {
          if (typeof handlerPrimero == "function")
            handlerPrimero(ev);
          formatoNumerico.formatearEntero(ev);

        };
        inpu.oninput = nuevoHandler;
        /***** */
        //   inpu.oninput = contexto.formatearEntero;

        $(inpu).addClass("text-end");
      });


      let decimales = document.querySelectorAll( selector_+ " .decimal");
      Array.prototype.forEach.call(decimales, function(inpu) {
        inpu.oninput = contexto.formatearDecimal;
        $(inpu).addClass("text-end");
      });
    }



  };
</script>
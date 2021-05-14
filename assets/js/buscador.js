class DataSearcher {
  constructor() {
    this.metodo = "POST";
    this.requestContentType = 'application/x-www-form-urlencoded', this.formato = "html";
    /** pdf  json  html  */

    this.dataLink = "#GRILL-URL-CUSTOM";
    this.url = "";
    this.outputTarget = "#grill";
    this.parametros = {};
    this.formatoHtml = this.formatoHtml.bind(this);
    this.formatoJson = this.formatoJson.bind(this);
    this.formatoExcel = this.formatoExcel.bind(this);
    this.formatoPdf = this.formatoPdf.bind(this);
    this.buscar = this.buscar.bind(this);
  }

  set setMetodo(arg) {
    this.metodo = arg;
  }

  set setRequestContentType(arg) {
    this.requestContentType = arg;
  }

  set setFormato(_arg) {
    this.formato = _arg;
  }

  set setUrl(ar) {
    this.url = ar;
  }

  set setDataLink(_arg) {
    this.dataLink = _arg;
    this.url = $(this.dataLink).val();
  }

  set setOutputTarget(_arg) {
    this.outputTarget = _arg;
  }

  set setParametros(_arg) {
    this.parametros = _arg;
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
    let respBlob = await req.blob(); //blob

    /* var file = window.URL.createObjectURL(respBlob);//transformar el blob a url
     let newWindow = window.open("", "_blank");
     newWindow.location.assign(file);//Attach a file to a window*/
    //Filesaver Js Lib

    let blobData = new Blob([respBlob], {
      type: "application/pdf;charset=utf-8"
    });
    let tituloDoc = $("title").text();
    saveAs(blobData, tituloDoc + ".pdf");
  }

  async formatoJson(ev) {
    if (ev != undefined) ev.preventDefault();
    this.formato = "json";
    let req = await this.buscar();
    let resp = await req.json();
    return resp;
  }

  async formatoExcel(ev) {
    if (ev != undefined) ev.preventDefault();
    this.formato = "json";
    let req = await this.buscar();
    let resp = await req.json();
    let tituloDoc = $("title").text();
    callToXlsGen_with_data(tituloDoc, resp);
  }

  prepararParametros() {
    if (this.requestContentType == 'application/x-www-form-urlencoded') return Object.entries(this.parametros).map(([clave, valor]) => clave + "=" + valor).join("&");
    if (this.requestContentType == 'application/json') return JSON.stringify(this.parametros);else "";
  }

  async buscar() {
    let grill_url = this.url;

    if (this.formato == "html") {
      show_loader(); //   $("#grill").html(loader);
    } //Formato de datos seleccionado


    let formato = this.formato;
    let metodoReq = this.metodo; //parametros

    let params = this.prepararParametros(); //Send

    let requestSetting = {
      method: metodoReq,
      headers: {
        'X-Requested-With': "XMLHttpRequest",
        'Content-Type': this.requestContentType,
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'formato': formato
      }
    };
    if (metodoReq.toUpperCase() == "POST") requestSetting.body = params;
    let req = await fetch(grill_url, requestSetting);
    hide_loader();
    return req;
  }

}

;
window.DataSearcher = new DataSearcher();
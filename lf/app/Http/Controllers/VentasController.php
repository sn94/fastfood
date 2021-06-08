<?php

namespace App\Http\Controllers;

use App\Helpers\Utilidades;
use App\Http\Controllers\Controller;
use App\Mail\TicketSender;
use App\Models\Combos;
use App\Models\Parametros;
use App\Models\Servicios;
use App\Models\Sesiones;
use App\Models\Stock;
use App\Models\Sucursal;
use App\Models\Usuario;
use App\Models\Ventas;
use App\Models\Ventas_det;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class VentasController extends Controller
{


    /**
     * 
     * Estados
     * 
     * A  Activa, confirmada
     * B Anulada
     * P Pendiente, esperando confirmacion
     */

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index()
    {

        //Ventas por sesion
        $SUCURSAL = session("SUCURSAL");
        $CAJERO = session("ID");
        $SESION = is_null(session("SESION")) ? FALSE : session("SESION");
        $FECHA_DESDE = request()->has("FECHA_DESDE") ?   request()->input("FECHA_DESDE") :  "";
        $FECHA_HASTA = request()->has("FECHA_HASTA") ?   request()->input("FECHA_HASTA") :  "";

        if ($SESION == FALSE) {
            //Ultima sesion de cajero
            $ultimaSesion =  Sesiones::where("SUCURSAL",  $SUCURSAL)->where("CAJERO",  $CAJERO)
                ->orderBy("FECHA_APE", "DESC")->first();
            if (!is_null($ultimaSesion))
                $SESION = $ultimaSesion->REGNRO;
        }
        /*Algun param Post ? **/
        $SUCURSAL = request()->has("SUCURSAL") ? request()->input("SUCURSAL") : $SUCURSAL;
        $CAJERO = request()->has("CAJERO") ? request()->input("CAJERO") : $CAJERO;

        $ventas = Ventas::where("SUCURSAL", $SUCURSAL)
            ->where("CAJERO", $CAJERO);
        //Filtrar por fecha
        if ($FECHA_DESDE != ""  &&  $FECHA_HASTA != "")
            $ventas = $ventas->where("ventas.FECHA", ">=", $FECHA_DESDE)
                ->where("ventas.FECHA", "<=", $FECHA_HASTA);
        else $ventas = $ventas->where("ventas.FECHA", "=", date('Y-m-d'));

        //Filtrar por sesion
        if (!$SESION)
            $ventas =  $ventas->where("SESION", $SESION);
        else {
            $ventas = $ventas->orderBy("created_at", "DESC");
        }

        $ventas = $ventas->select("ventas.*", DB::raw("FORMAT(ventas.TOTAL, 0, 'de_DE' ) AS TOTAL"));


        //El formato de los datos
        $formato =  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json") {
            $ventas =  $ventas->get();
            return response()->json($ventas);
        }
        //O PDF
        if ($formato == "pdf") {
            $ventas =  $ventas->get();
            //Fechas
            $titulo = "Ventas ";
            $cajeroModel =  Usuario::find($CAJERO);
            if ($FECHA_DESDE != ""  &&  $FECHA_HASTA != "") {
                $fecha_d = date('d/m/Y',  strtotime($FECHA_DESDE));
                $fecha_h = date('d/m/Y',  strtotime($FECHA_HASTA));
                $titulo .= " (Desde el $fecha_d hasta $fecha_h)";
            } else {
                $titulo .= date("d/m/Y");
            }
            return $this->responsePdf(
                "ventas.index.grill",
                ["VENTAS" => $ventas, "CAJERO" => $cajeroModel],
                $titulo
            );
        }

        //O por defecto HTML
        $ventas =  $ventas->with("cajero")->paginate(20);
        if (request()->ajax())
            return view("ventas.index.grill", ['VENTAS' =>  $ventas]);
        else
            return view("ventas.index.index", ['VENTAS' =>  $ventas]);
    }




    /**
     * 
     * Funciones especificas
     */




    public function filtrar($filterParam = NULL)
    {
        //Setear el numero de pagina
        $numeroPagina =   request()->has("page") ? request()->input("page") : "1";
        $_GET['page'] = $numeroPagina;

        //Determinar el filtro elegido
        $filtro = is_null($filterParam) ?  (request()->has("FILTRO") ?  request()->input("FILTRO")  : "1")  : $filterParam;

        //Enviar el formulario del filtro elegido
        //personaliza la consulta
        if (request()->isMethod("GET")  &&  request()->ajax()) {
            return view("ventas.reportes.filters.filter$filtro");
        }

        //***
        //*****
        //En Post

        $VENTAS =  [];
        $TITULO = "";


        if (preg_match("/^3/", $filtro)) $filtro = 3;
        $nombreFuncionFiltro = "filtro$filtro";

        try {

            $resultado =  $this->{$nombreFuncionFiltro}();

            $TITULO =  $resultado['titulo'];
            $VENTAS =  $resultado['data'];
        } catch (Exception $x) {
            return response()->json(['err' =>  $x->getMessage()]);
        }

        //Content Type solicitado
        //El formato de los datos
        $formato =  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json") {

            try {
                $VENTAS =  $VENTAS->get();
                //Solicitan datos json incluido un titulo descriptivo
                $DESCRIPTIVO = request()->has("DESCRIPTIVO") ? request()->input("DESCRIPTIVO") : "N";
                if ($DESCRIPTIVO ==  "N")
                    return response()->json($VENTAS);
                else
                    return response()->json(["titulo" =>  $TITULO, "data" => $VENTAS]);
            } catch (Exception $e) {
                return response()->json(['err' =>  $e->getMessage()]);
            }
        }

        if ($formato == "pdf") {
            try {
                $VENTAS =  $VENTAS->get();

                return $this->responsePdf("ventas.reportes.views.filter$filtro",  $VENTAS, $TITULO);
            } catch (Exception $e) {
                return response()->json(['err' =>  $e->getMessage()]);
            }
        }


        //Formato html
        try {

            $VENTAS =   $VENTAS->paginate(20);
        } catch (Exception  $e) {
            return response()->json(['err' =>  $e->getMessage()]);
        }

        if (request()->ajax())
            return view("ventas.reportes.views.filter$filtro", ['FILTRO' => $filtro, 'VENTAS' => $VENTAS]);
        else
            return view('ventas.reportes.index', ['FILTRO' => $filtro, 'VENTAS' => $VENTAS]);
    }





    /**lO MAS VENDIDO EN LAS SUCURSALES */
    public function filtro1()
    {
        $tipo_stock = "";
        $familia_stock = "";

        $sucursal =   request()->has("SUCURSAL") ? request()->input("SUCURSAL") : "";
        $tipo_stock =   request()->has("TIPO_STOCK") ? request()->input("TIPO_STOCK") : "";
        $familia_stock =   request()->has("FAMILIA_STOCK") ? request()->input("FAMILIA_STOCK") : "";
        $FECHA_DESDE = request()->has("FECHA_DESDE") ?   request()->input("FECHA_DESDE") :  "";
        $FECHA_HASTA = request()->has("FECHA_HASTA") ?   request()->input("FECHA_HASTA") :  "";
        $mes =   request()->has("MES") ? request()->input("MES") : date("m");
        $anio = request()->has("ANIO") ? request()->input("ANIO") : date("Y");


        $loMasVendido =  Ventas_det::join("ventas", "ventas.REGNRO", "=", "ventas_det.VENTA_ID")
            ->join("stock", "stock.REGNRO", "=",  "ventas_det.ITEM")
            ->join("familia", "familia.REGNRO", "=",  "stock.FAMILIA")
            ->join("sucursal", "ventas.SUCURSAL", "=",  "sucursal.REGNRO");
        if ($sucursal != "")
            $loMasVendido = $loMasVendido->where("ventas.SUCURSAL", $sucursal);
        if ($tipo_stock != "")
            $loMasVendido = $loMasVendido->where("stock.TIPO", $tipo_stock);
        if ($familia_stock != "")
            $loMasVendido = $loMasVendido->where("stock.FAMILIA", $familia_stock);

        if ($FECHA_DESDE != ""  &&  $FECHA_HASTA != "")
            $loMasVendido = $loMasVendido->where("ventas.FECHA", ">=", $FECHA_DESDE)
                ->where("ventas.FECHA", "<=", $FECHA_HASTA);

        $loMasVendido = $loMasVendido->groupBy("ventas_det.ITEM")->groupBy("ventas.SUCURSAL")
            ->select(
                "sucursal.REGNRO AS SUCURSAL_ID",
                "familia.DESCRIPCION AS FAMILIA",
                "sucursal.DESCRIPCION AS SUCURSAL_NOMBRE",
                "stock.DESCRIPCION",
                "stock.DESCR_CORTA",
                "stock.TIPO",

                DB::raw('count(ventas_det.REGNRO) AS NUMERO_VENTAS')
            )
            ->orderByRaw("count(ventas_det.REGNRO)  DESC");


        $sucursalModel = Sucursal::find($sucursal);
        $descripcionSucursal = "";
        if (is_null($sucursalModel))
            $descripcionSucursal = " LOS LOCALES";
        else
            $descripcionSucursal = $sucursalModel->MATRIZ == "S" ? "CASA CENTRAL" : "SUC. $sucursalModel->DESCRIPCION";
        $titulo = "LO MÁS VENDIDO EN $descripcionSucursal";
        if ($FECHA_DESDE != ""  &&  $FECHA_HASTA != "") {
            $desde = Utilidades::fecha_f($FECHA_DESDE);
            $hasta = Utilidades::fecha_f($FECHA_HASTA);
            $titulo .= " Del " . $desde . " hasta el " . $hasta;
        }
        return  ['titulo' => $titulo,  "data" =>   $loMasVendido];
    }


    /**las ventas discriminadas por forma de pago, y total de venta , cajero*/
    public function filtro2()
    {

        $sucursal =   request()->has("SUCURSAL") ? request()->input("SUCURSAL") : "";
        $medio_pago =   request()->has("FORMA_PAGO") ? request()->input("FORMA_PAGO") : "";
        $FECHA_DESDE = request()->has("FECHA_DESDE") ?   request()->input("FECHA_DESDE") :  "";
        $FECHA_HASTA = request()->has("FECHA_HASTA") ?   request()->input("FECHA_HASTA") :  "";
        $estado =   request()->has("ESTADO") ? request()->input("ESTADO") : "";
        $cajero =   request()->has("CAJERO") ? request()->input("CAJERO") : "";


        $loMasVendido =  Ventas::join("sucursal", "ventas.SUCURSAL", "=",  "sucursal.REGNRO")
            ->join("clientes", "clientes.REGNRO", "=",  "ventas.CLIENTE")
            ->join("usuarios", "usuarios.REGNRO", "=", "ventas.CAJERO");

        if ($sucursal != "")
            $loMasVendido = $loMasVendido->where("ventas.SUCURSAL", $sucursal);

        if ($medio_pago != "")
            $loMasVendido = $loMasVendido->where("ventas.FORMA", $medio_pago);
        if ($estado != "")
            $loMasVendido = $loMasVendido->where("ventas.ESTADO", $estado);
        if ($cajero != "")
            $loMasVendido = $loMasVendido->where("ventas.CAJERO", $cajero);
        if ($FECHA_DESDE != ""  &&  $FECHA_HASTA != "")
            $loMasVendido = $loMasVendido->where("ventas.FECHA", ">=", $FECHA_DESDE)
                ->where("ventas.FECHA", "<=", $FECHA_HASTA);

        $loMasVendido = $loMasVendido->select(
            "sucursal.REGNRO AS SUCURSAL_ID",
            "sucursal.DESCRIPCION AS SUCURSAL_NOMBRE",
            "clientes.NOMBRE AS cliente_nom",
            "ventas.REGNRO AS FACTURA",
            "usuarios.NOMBRES as CAJERO",
            DB::raw("IF( ventas.ESTADO = 'A', 'ACTIVA', 'ANULADA') AS ESTADO"),
            "FECHA",
            DB::raw(" format(TOTAL, 0, 'de_DE') as TOTAL "),
            "ventas.FORMA AS FORMA_PAGO"
        )->with("cajero");

        //Formular titulo
        $sucursalModel = Sucursal::find($sucursal);
        $descripcionSucursal = "";
        if (is_null($sucursalModel))
            $descripcionSucursal = " EN CADA LOCAL";
        else
            $descripcionSucursal = $sucursalModel->MATRIZ == "S" ? "CASA CENTRAL" : "SUC. $sucursalModel->DESCRIPCION";
        $titulo = "VENTAS - $descripcionSucursal";
        if ($FECHA_DESDE != ""  &&  $FECHA_HASTA != "") {
            $desde = Utilidades::fecha_f($FECHA_DESDE);
            $hasta = Utilidades::fecha_f($FECHA_HASTA);
            $titulo .= " Del " . $desde . " hasta el " . $hasta;
        }
        return  ['titulo' => $titulo,  "data" =>   $loMasVendido];
    }




    //medios de pago preferidos
    private function filtro3()
    {
        //Cuanto se vendio, por tarjeta, giro, efectivo
        $sucursal =   request()->has("SUCURSAL") ? request()->input("SUCURSAL") : session("SUCURSAL");

        $medio_pago =   Ventas::where("SUCURSAL", $sucursal)->select(DB::raw("count(REGNRO) AS NUMERO_VENTAS"),  "FORMA")->groupBy("FORMA")
            ->orderByRaw(" count(REGNRO) DESC ");

        $sucursalModel = Sucursal::find($sucursal);
        $descripcionSucursal = "";
        if (!is_null($sucursalModel))
            $descripcionSucursal = $sucursalModel->MATRIZ == "S" ? "CASA CENTRAL" : "SUC. $sucursalModel->DESCRIPCION";

        $titulo = "MEDIOS DE PAGO PREFERIDOS - $descripcionSucursal";
        return  ['titulo' =>  $titulo, "data" =>   $medio_pago];
    }



    //La mayor recaudacion de los ultimos 6 meses
    private function filtro4()
    {

        $sucursal =   request()->has("SUCURSAL") ? request()->input("SUCURSAL") : session("SUCURSAL");

        $mes =  request()->has("MES") ? request()->input("MES") : date("m");
        $anio =  request()->has("ANIO") ? request()->input("ANIO") : date("Y");

        $medio_pago =   Ventas::where("SUCURSAL", $sucursal)->select(DB::raw("sum(TOTAL) AS TOTAL_RECAUDADO"),  DB::raw("concat(MONTH(FECHA) , concat('/',year(FECHA)) ) AS MES"))
            ->groupByRaw("MONTH(FECHA)")
            ->groupByRaw("YEAR(FECHA)")
            ->orderByRaw(" MONTH(FECHA) DESC ")
            ->orderByRaw("YEAR(FECHA) DESC")
            ->limit(6);

        $sucursalModel = Sucursal::find($sucursal);
        $descripcionSucursal = "";
        if (!is_null($sucursalModel))
            $descripcionSucursal = $sucursalModel->MATRIZ == "S" ? "CASA CENTRAL" : "SUC. $sucursalModel->DESCRIPCION";

        $titulo = "RECAUDACIÓN Gs. - $descripcionSucursal";
        return  ['titulo' =>  $titulo, "data" =>   $medio_pago];
    }










    private function ejecutar_actualizar_stock($r1, $r2)
    { //item cantidad

        $DetalleVentaItemModel =  Stock::find($r1);

        //Actualizar existencia Si no es un Combo
        if ($DetalleVentaItemModel->TIPO != "COMBO") {
            (new StockController())->actualizar_existencia($r1, $r2, 'DEC');
        }

        //Actualizar EXISTENCIA de ingredientes Si es producto elaborado
        if (Parametros::where("SUCURSAL", session("SUCURSAL"))->first()->DESCONTAR_MP_EN_VENTA == "S") {

            $receta = Stock::find($r1)->receta;
            if (sizeof($receta)) {
                foreach ($receta as $item) (new StockController())->actualizar_existencia($item->MPRIMA_ID, $item->CANTIDAD, 'DEC');
            }
            /** Receta definida? */

            if ($DetalleVentaItemModel->TIPO == "COMBO") {
                //Descontar productos del combo
                $comboModel = Combos::where("COMBO_ID", $r1)->get();
                foreach ($comboModel as $comboItem) (new StockController())->actualizar_existencia($comboItem->STOCK_ID,  $comboItem->CANTIDAD, 'DEC');
            }
        }
        /** EnD Actualizacion de stock de ingrediente segun parametro */
    }

    public function create()
    {
        if (request()->getMethod()  ==  "GET") {

            // Sesion abierta?
            if (!session()->has("SESION"))
                return view("ventas.no_permitido");
            return view('ventas.proceso.index');
        } else {


            $nventa =  new Ventas();
            $DATOS = request()->input();
            //Cliente
            if ($DATOS['CABECERA']['CLIENTE']  ==  "") {
                return response()->json(['err' => 'Por favor cargue un cliente']);
            }
            try {
                DB::beginTransaction();
                //Cabecera
                //Venta Con delivery?
                if (array_key_exists("DELIVERY", $DATOS['CABECERA'])  &&  $DATOS['CABECERA']['DELIVERY'] == "S") {
                    $DATOS['CABECERA']['ESTADO'] = "P";
                } else  $DATOS['CABECERA']['ESTADO'] = "A";

                $DATOS["CABECERA"]['SESION'] =  session("SESION");
                $nventa->fill($DATOS["CABECERA"]);
                $nventa->save();


                //Detalle
                $DETALLE = $DATOS['DETALLE'];

                for ($numero = 0; $numero  <  sizeof($DETALLE); $numero++) :

                    $DETALLAZO = $DETALLE[$numero];
                    $r1 = $DETALLAZO['ITEM'];
                    $r2 = $DETALLAZO['CANTIDAD'];
                    $r3 = $DETALLAZO['P_UNITARIO'];
                    $p_exe =  0;
                    $p_10 = 0;
                    $p_5 = 0;

                    $TRIBUTO = (Stock::find($DETALLAZO['ITEM']))->TRIBUTO;
                    if ($TRIBUTO  ==  "0") {
                        $p_exe =  $r3 *    $r2;
                        $p_10 = 0;
                        $p_5 = 0;
                    }
                    if ($TRIBUTO  ==  "10") {
                        $p_10 =  $r3 *    $r2;
                        $p_exe = 0;
                        $p_5 = 0;
                    }

                    if ($TRIBUTO  ==  "5") {
                        $p_5 =  $r3 *    $r2;
                        $p_10 = 0;
                        $p_exe = 0;
                    }
                    $detalleDeVenta_regs =
                        ['VENTA_ID' => $nventa->REGNRO, 'ITEM' => $r1, 'CANTIDAD' => $r2, 'P_UNITARIO' => $r3, 'EXENTA' => $p_exe, 'TOT5' => $p_5,  'TOT10' => $p_10];


                    // Tratamiento del Stock 
                    if (!(array_key_exists("DELIVERY", $DATOS['CABECERA'])  &&  $DATOS['CABECERA']['DELIVERY'] == "S")) {
                        $this->ejecutar_actualizar_stock($r1, $r2);
                    }

                    (new Ventas_det())->fill($detalleDeVenta_regs)->save();
                endfor;




                DB::commit();
                return response()->json(['ok' => $nventa->REGNRO]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }







    public function ticket($IDVENTA)
    {

        $venta = Ventas::find($IDVENTA);
        $detalle = $venta->detalle;

        $formato =  request()->header("formato");


        if ($formato ==  "email") {

            $email =  $venta->cliente->EMAIL;

            if ($email ==  "")
                return response()->json(['err' =>  "No se registra un email para este cliente"]);
            else {


                Mail::to($email)->send((new TicketSender($venta)));
                return response()->json(['ok' =>  "enviado"]);
            }
        } else {
            $delivery = NULL;
            if ($venta->DELIVERY == "S")
                $delivery = Servicios::find($venta->SERVICIO);
            return view(
                "ventas.proceso.ticket.version_impresa",
                ["VENTA" => $venta, "DETALLE" => $detalle, "DELIVERY" =>  $delivery]
            );
        }
    }









    public   function  anular($IDVENTA)
    {

        try {
            $venta = Ventas::find($IDVENTA);
            $venta->ESTADO = "B";
            $venta->save();
            return response()->json(['ok' =>  'VENTA ANULADA']);
        } catch (Exception $e) {
            return response()->json(['err' =>  $e]);
        }
    }

    public   function  confirmar($IDVENTA= null)
    {

        if (request()->isMethod("GET"))
            return view("ventas.confirmar.index", ['REGNRO' =>  $IDVENTA]);
        try {
            DB::beginTransaction();
            $idventa = request()->input("REGNRO");
            $venta = Ventas::find($idventa);
            if ($venta->ESTADO == "A")
                return response()->json(['err' =>  "Esta venta ya ha sido confirmada anteriormente"]);

            $detalle_venta = $venta->detalle;
            foreach ($detalle_venta as $det)
                $this->ejecutar_actualizar_stock($det->ITEM, $det->CANTIDAD);
            //oBTENER MEDIO DE PAGO DEFINITIVO
            $forma_pago = request()->input("FORMA");
            $venta->FORMA =  $forma_pago;
            $venta->ESTADO = "A";
            $venta->save();
            DB::commit();
            return response()->json(['ok' =>  'VENTA CONFIRMADA']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['err' =>  $e->getMessage()]);
        }
    }

    public  function view($VENTAID = NULL)
    {
        if (!is_null($VENTAID)) {

            $header =  Ventas::find($VENTAID);
            $detalles = $header->detalle;
            $delivery = NULL;
            if ($header->DELIVERY == "S")
                $delivery = Servicios::find($header->SERVICIO);
            return view("ventas.view.index", ['HEADER' =>  $header, 'DETALLE' => $detalles,   "DELIVERY" => $delivery]);
        }
    }
}

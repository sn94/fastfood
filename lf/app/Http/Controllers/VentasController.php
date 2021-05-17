<?php

namespace App\Http\Controllers;

use App\Helpers\Utilidades;
use App\Http\Controllers\Controller;
use App\Mail\TicketSender;
use App\Models\Clientes;
use App\Models\Parametros;
use App\Models\Salidas;
use App\Models\Salidas_detalles;
use App\Models\Sesiones;
use App\Models\Stock;
use App\Models\Stock_existencias;
use App\Models\Sucursal;
use App\Models\Ventas;
use App\Models\Ventas_det;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\MockObject\Rule\Parameters;

class VentasController extends Controller
{

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

        if ($SESION == FALSE) {
            //Ultima sesion de cajero
            $ultimaSesion =  Sesiones::where("SUCURSAL",  $SUCURSAL)->where("CAJERO",  $CAJERO)->orderBy("FECHA_APE", "DESC")->first();
            if (!is_null($ultimaSesion))
                $SESION = $ultimaSesion->REGNRO;
        }
        /*Algun param Post ? **/
        $SUCURSAL = request()->has("SUCURSAL") ? request()->input("SUCURSAL") : $SUCURSAL;
        $CAJERO = request()->has("CAJERO") ? request()->input("CAJERO") : $CAJERO;

        $ventas = Ventas::where("SUCURSAL", $SUCURSAL)
            ->where("CAJERO", $CAJERO)
            ->where("FECHA",date("Y-m-d"));
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

        $ventas =  $ventas->paginate(10);


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

            $VENTAS =  is_array($VENTAS)  ?  $VENTAS :  $VENTAS->paginate(15);
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
            ->join("clientes", "clientes.REGNRO", "=",  "ventas.CLIENTE");;
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

            DB::raw("IF( ESTADO= 'A', 'ACTIVA', 'ANULADA') AS ESTADO"),
            "FECHA",
            DB::raw(" format(TOTAL, 0, 'de_DE') as TOTAL "),
            "ventas.FORMA AS FORMA_PAGO"
        );

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











    public function create()
    {
        if (request()->getMethod()  ==  "GET") {

            //Sesion abierta?
            if (!session()->has("SESION"))
                return view("ventas.no_permitido");


            return view('ventas.proceso.index');
        } else {


            $nventa =  new Ventas();
            $DATOS = request()->input();
            //Cliente
            if ($DATOS['CABECERA']['CLIENTE']  ==  "") {
                //cliente predeterminado
                $DEFAULTCLI = Parametros::first();
                if (is_null($DEFAULTCLI)) return response()->json(['err' => 'Por favor cargue un cliente']);
                else {
                    $defaulcli =  $DEFAULTCLI->CLIENTE_PORDEFECTO;
                    if ($defaulcli == ""   ||   is_null($defaulcli)) return response()->json(['err' => 'Por favor cargue un cliente']);
                }
            }
            try {
                DB::beginTransaction();
                //Cabecera
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
                    $sql =
                        ['VENTA_ID' => $nventa->REGNRO, 'ITEM' => $r1, 'CANTIDAD' => $r2, 'P_UNITARIO' => $r3, 'EXENTA' => $p_exe, 'TOT5' => $p_5,  'TOT10' => $p_10];



                    //Actualizar existencia
                    $existencia = Stock_existencias::where("SUCURSAL", session("SUCURSAL"))
                        ->where("STOCK_ID",  $r1)->first();
                    $existencia->CANTIDAD =  $existencia->CANTIDAD - $r2;
                    $existencia->save(); //disminuir

                    //Actualizar EXISTENCIA de ingredientes
                    if (Parametros::first()->DESCONTAR_MP_EN_VENTA == "S") {
                        $receta = Stock::find($r1)->receta;
                        if (sizeof($receta)) {
                            foreach ($receta as $item) {
                                $salida = (new Salidas());
                                $salida->SUCURSAL = session("SUCURSAL");
                                $salida->FECHA =  date("Y-m-d");
                                $salida->TIPO_SALIDA = "MP";
                                $salida->REGISTRADO_POR = session("ID");
                                $salida->CONCEPTO = "DISMINUCIÓN DE MATERIA PR. P/ VENTA DE PROD. ELABORADO";
                                $salida->save();
                                $det_salida = (new Salidas_detalles());
                                $det_salida->SALIDA_ID =  $salida->REGNRO;
                                $det_salida->ITEM =  $item->MPRIMA_ID;
                                $det_salida->CANTIDAD = $item->CANTIDAD;
                                $det_salida->TIPO = "MP";
                                $det_salida->save();
                                //reflejar en existencia
                                $existencia_mp = Stock_existencias::where("SUCURSAL", session("SUCURSAL"))
                                    ->where("STOCK_ID",  $item->MPRIMA_ID)->first();
                                $existencia_mp->CANTIDAD =  $existencia_mp->CANTIDAD - $item->CANTIDAD;
                                $existencia_mp->save(); //disminuir
                            }
                        }
                        /** Receta definida? */
                    }
                    /** Actualizacion de stock de ingrediente segun parametro */



                    (new Ventas_det())->fill($sql)->save();
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
                var_dump(Mail::to($email)->send(new TicketSender($venta)));
                return response()->json(['ok' =>  "enviado"]);
            }
        } else    return view("ventas.proceso.ticket.version_impresa", ["VENTA" => $venta, "DETALLE" => $detalle]);
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



    public  function view($VENTAID = NULL)
    {
        if (!is_null($VENTAID)) {

            $header =  Ventas::find($VENTAID);
            $detalles = $header->detalle;
            return view("ventas.view.index", ['HEADER' =>  $header, 'DETALLE' => $detalles]);
        }
    }
}

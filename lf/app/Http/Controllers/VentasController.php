<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\TicketSender;
use App\Models\Clientes;
use App\Models\Parametros;
use App\Models\Sesiones;
use App\Models\Stock;
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
            ->where("CAJERO", $CAJERO);
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



    public function productos_mas_vendidos()
    {



        $todo =   request()->has("TODO") ? request()->input("TODO") : "N";
        $sucursal = "";
        if ($todo == "N")
            $sucursal =   request()->has("SUCURSAL") ? request()->input("SUCURSAL") : session("SUCURSAL");

        $mes =   request()->has("MES") ? request()->input("MES") : date("m");
        $anio = request()->has("ANIO") ? request()->input("ANIO") : date("Y");


        $loMasVendido =  Ventas_det::join("ventas", "ventas.REGNRO", "=", "ventas_det.VENTA_ID")
            ->join("stock", "stock.REGNRO", "=",  "ventas_det.ITEM")
            ->join("sucursal", "ventas.SUCURSAL", "=",  "sucursal.REGNRO");
        if ($sucursal != "")
            $loMasVendido = $loMasVendido->where("ventas.SUCURSAL", $sucursal);

        $loMasVendido = $loMasVendido->whereRaw(" month(ventas.FECHA)", $mes)
            ->whereRaw(" year(ventas.FECHA)", $anio)
            ->groupBy("ventas_det.ITEM")
            ->select("sucursal.DESCRIPCION",  "stock.DESCRIPCION", DB::raw('count(ventas_det.REGNRO) AS NUMERO_VENTAS'))->get();

        $sucursalModel = Sucursal::find($sucursal);
        $descripcionSucursal = $sucursalModel->MATRIZ == "S" ? "CASA CENTRAL" : "SUC. $sucursalModel->DESCRIPCION";
        $titulo = "LO MÁS VENDIDO EN $descripcionSucursal";
        return response()->json(['titulo' => $titulo,  "data" =>   $loMasVendido]);
    }

    public function medios_pagos_preferidos()
    {
        //Cuanto se vendio, por tarjeta, giro, efectivo
    }

    public function categoria_productos_populares()
    {
        //cuanto se vende de pizzas, hamburguesas ...
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
        } else    return view("ventas.proceso.ticket.print.index", ["VENTA" => $venta, "DETALLE" => $detalle]);
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

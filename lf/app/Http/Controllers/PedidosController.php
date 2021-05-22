<?php

namespace App\Http\Controllers;

use App\Helpers\Utilidades;
use App\Http\Controllers\Controller;
use App\Models\Nota_pedido;
use App\Models\Nota_pedido_detalles;
use App\Models\Salidas;
use App\Models\Salidas_detalles;
use App\Models\Stock;
use App\Models\Stock_existencias;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;


class PedidosController extends Controller
{



    //pedidos de las sucursales
    public function  sucursales()
    {

        // $stockActual = ($stock->ENTRADAS + $stock->ENTRADA_PE + $stock->ENTRADA_RESIDUO) - ($stock->SALIDAS + $stock->SALIDA_VENTA);
        //Pedido
        $pedido = Nota_pedido::join("nota_pedido_detalles", "nota_pedido_matriz.REGNRO",  "=", "nota_pedido_detalles.NPEDIDO_ID")
            ->join("stock", "nota_pedido_detalles.ITEM", "=", "stock.REGNRO")
            ->join("unimed", "unimed.REGNRO", "=", "stock.MEDIDA")
            ->where("nota_pedido_matriz.SUCURSAL", session("SUCURSAL"))

            ->select("nota_pedido_matriz.*", "nota_pedido_detalles.ITEM", "nota_pedido_detalles.CANTIDAD", "stock.DESCRIPCION", "unimed.UNIDAD as MEDIDA");
        Nota_pedido::where("SUCURSAL", session("SUCURSAL"))
        ->orderBy("ESTADO")->with("nota_pedido_detalles");

        if (request()->ajax())
            return view("pedidos.sucursales.grill",  ['PEDIDOS' => $pedido->paginate(20)]);
        else
            return view("pedidos.sucursales.index",  ['PEDIDOS' => $pedido->paginate(20)]);
    }

    public function  realizados()
    {

        // $stockActual = ($stock->ENTRADAS + $stock->ENTRADA_PE + $stock->ENTRADA_RESIDUO) - ($stock->SALIDAS + $stock->SALIDA_VENTA);
        //Pedido
        $pedido = Nota_pedido::join("nota_pedido_detalles", "nota_pedido_matriz.REGNRO",  "=", "nota_pedido_detalles.NPEDIDO_ID")
            ->join("stock", "nota_pedido_detalles.ITEM", "=", "stock.REGNRO")
            ->join("unimed", "unimed.REGNRO", "=", "stock.MEDIDA")
            ->where("nota_pedido_matriz.SUCURSAL", session("SUCURSAL"))

            ->select("nota_pedido_matriz.*", "nota_pedido_detalles.*", "stock.DESCRIPCION", "unimed.UNIDAD as MEDIDA")
            ->orderBy("ESTADO")
            ->with("nota_pedido_detalles");

        if (request()->ajax())
            return view("pedidos.realizados.grill",  ['PEDIDOS' => $pedido->paginate(20)]);
        else
            return view("pedidos.realizados.index",  ['PEDIDOS' => $pedido->paginate(20)]);
    }



    //LO vendido 
    public function unidades_vendidas()
    {

        $productosVendidos =  Stock_existencias::join( "stock", "stock.REGNRO", "=", "stock_existencias.STOCK_ID")
        ->join("unimed", "unimed.REGNRO", "=", "stock.MEDIDA")
        ->select("stock.*", "stock_existencias.CANTIDAD", "unimed.UNIDAD as MEDIDA");
        
        //->buscar_productos(request(), "VENTA"); //Filtrar solo los elaborados y para venta
        $productosVendidos =  $productosVendidos->paginate(20);

        if (request()->ajax())
            return view("pedidos.unidades_vendidas.grill", ['PRODUCTOS' =>  $productosVendidos]);
        else
            return view("pedidos.unidades_vendidas.index", ['PRODUCTOS' =>  $productosVendidos]);
    }






    public function create($STOCKID =  null)
    {
        if (request()->isMethod("GET"))
            return view("pedidos.forms.pedido", ['STOCK' =>  Stock::find($STOCKID)]);

        //Recibir cantidad
        $STOCKID =  request()->input("ITEM");
        $cantidad = request()->input("CANTIDAD");
        $solicitado_por = request()->input("SOLICITADO_POR");
        try {
            DB::beginTransaction();
            $pedido = new Nota_pedido();
            $pedido->SUCURSAL = session("SUCURSAL");
            $pedido->FECHA = date("Y-m-d");
            $pedido->SOLICITADO_POR =  $solicitado_por;
            $pedido->REGISTRADO_POR =  session("ID");
            $pedido->CONCEPTO = "SOLICITUD DE PRODUCTOS A CASA MATRIZ";
            $pedido->ESTADO = "P";
            $pedido->save();
            //detalle
            $detalle_pedido = new Nota_pedido_detalles();
            $detalle_pedido->ITEM =  $STOCKID;
            $detalle_pedido->TIPO = Stock::find($STOCKID)->TIPO;
            $detalle_pedido->CANTIDAD =  Utilidades::limpiar_numero($cantidad);
            $detalle_pedido->NPEDIDO_ID =  $pedido->REGNRO;
            $detalle_pedido->save();
            DB::commit();
            return response()->json(['ok' => "Pedido N° $pedido->REGNRO  registrado"]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['err' => $e->getMessage()]);
        }
    }



    public function recibir($PEDIDOID = NULL)
    {
        if (request()->isMethod("GET")) {
            $pedido = Nota_pedido::find($PEDIDOID)->nota_pedido_detalles;
            return view("pedidos.realizados.recibir",  ['PEDIDO' =>  $pedido]);

        } else {
            $datos = request()->input();
            $cantidad = $datos['CANTIDAD'];
            $item = $datos['ITEM'];
            $npedido =  $datos['NPEDIDO_ID'];
            try {

                DB::beginTransaction();

                $pedido = Nota_pedido::find($npedido);
                $pedido->ESTADO = "F"; //FINALIZADO
                $pedido->save();
                  //Actualizar existencia
            (new StockController())->actualizar_existencia($item, $cantidad, 'INC');
                


                DB::commit();
                return response()->json(['ok' => "Pedido finalizado exitosamente"]);
            } catch (Exception $ex) {
                DB::rollBack();
                return response()->json(['err' => $ex->getMessage()]);
            }
        }
    }

     



    public  function aprobar($IDPEDIDO = NULL)
    {

        if (request()->isMethod("GET"))
            return view("pedidos.sucursales.aprobacion",   ['PEDIDO_ID' =>  $IDPEDIDO]);

        $IDPEDIDO = request()->input("PEDIDO_ID");
        $tipoDeAccion = request()->input("opcion_aprobacion");
        $autoriza = request()->input("AUTORIZADO_POR"); //quien autoriza la salida
        //vERIFICAR TIPO DE ACCION
        //NO HAY EXISTENCIA
        //CANTIDAD DIFERENTE
        //100% APROBADO  
       
        try {
            DB::beginTransaction();

            $PEDIDO = Nota_pedido::find($IDPEDIDO);
            $PEDIDO->RECIBIDO_POR = $autoriza; //quien recibe el pedido y lo despacha
            $PEDIDO->received_by= session("ID");

            if ($tipoDeAccion ==  "SIN_STOCK") {

                $PEDIDO->OBSERVACION = "SIN STOCK";
                $PEDIDO->ESTADO = "R"; //Rechazado
                $PEDIDO->save();
                DB::commit();
                return   response()->json(['ok' => "El pedido fue desaprobado"]);
            } else $PEDIDO->ESTADO = "A";

            $PEDIDO->save();


            //Disminuir esto en Matriz
            //Detalle de productos solicitados
            $detalle_pedido =  $PEDIDO->nota_pedido_detalles;

            $ITEM =   $detalle_pedido[0]->ITEM;
            $CANTIDAD =   $detalle_pedido[0]->CANTIDAD; //Esta cantidad es editable

            //Si el despachante cambio la cantidad solicitada
            if ($tipoDeAccion == "CANTIDAD_EDITADA")
                $CANTIDAD =  Utilidades::limpiar_numero(request()->input("CANTIDAD"));

           $elDetalle=  Nota_pedido_detalles::where("NPEDIDO_ID", $IDPEDIDO)->where("ITEM", $ITEM)->first();
            $elDetalle->CANT_ACEPTADA= $CANTIDAD;
            $elDetalle->save();

            
            //Actualizar existencia
            (new StockController())->actualizar_existencia($ITEM, $CANTIDAD, 'DEC');

            DB::commit();
            return   response()->json(['ok' => "El pedido fue aprobado exitosamente"]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['err' => $e->getMessage()]);
        }
    }
}

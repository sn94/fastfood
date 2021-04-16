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
use App\Models\Ventas;
use App\Models\Ventas_det;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;


class PedidosController extends Controller
{

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index()
    {

        $productosVendidos =  (new StockController())->buscar_productos(request(), "VENTA"); //Filtrar solo los elaborados y para venta
        $productosVendidos =  $productosVendidos->paginate(10);

        if (request()->ajax())
            return view("pedidos.vendidos.grill", ['PRODUCTOS' =>  $productosVendidos]);
        else
            return view("pedidos.vendidos.index", ['PRODUCTOS' =>  $productosVendidos]);
    }





    public function  list($STOCKID)
    {

        //Calcular stock

        $stock =  Stock::find($STOCKID);
        // $stockActual = ($stock->ENTRADAS + $stock->ENTRADA_PE + $stock->ENTRADA_RESIDUO) - ($stock->SALIDAS + $stock->SALIDA_VENTA);
        //Pedido
        $pedido = Nota_pedido::join("nota_pedido_detalles", "nota_pedido_matriz.REGNRO",  "=", "nota_pedido_detalles.NPEDIDO_ID")
            ->where("nota_pedido_matriz.SUCURSAL", session("SUCURSAL"))
            ->where("ESTADO", "<>", "F")
            ->where("ITEM", $stock->REGNRO)
            ->select("nota_pedido_matriz.*", "nota_pedido_detalles.*");


        if (request()->ajax())
            return view("pedidos.index.grill",  ['PEDIDOS' => $pedido->paginate(20)]);
        else
            return view("pedidos.index.index",  ['STOCK' => $stock, 'PEDIDOS' => $pedido->paginate(20)]);
    }


    public function create($STOCKID =  null)
    {
        if (request()->isMethod("GET"))
            return view("pedidos.forms.pedido", ['STOCK' =>  Stock::find($STOCKID)]);

        //Recibir cantidad
        $STOCKID =  request()->input("ITEM");
        $cantidad = request()->input("CANTIDAD");
        try {
            DB::beginTransaction();
            $pedido = new Nota_pedido();
            $pedido->SUCURSAL = session("SUCURSAL");
            $pedido->FECHA = date("Y-m-d");
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
            return view("pedidos.forms.recibido",  ['PEDIDO' =>  $pedido]);
        } else {
            $datos = request()->input();
            $cantidad = $datos['CANTIDAD'];
            $item = $datos['ITEM'];
            $npedido =  $datos['NPEDIDO_ID'];
            try {

                DB::transaction();

                $pedido = Nota_pedido::find($npedido);
                $pedido->ESTADO = "F"; //FINALIZADO
                $pedido->save();

                //Actualizar existencia
                $existencia = Stock_existencias::where("SUCURSAL", session("SUCURSAL"))
                    ->where("STOCK_ID", $item)->first();
                $existencia->CANTIDAD =  $existencia->CANTIDAD +  $cantidad;
                $existencia->save(); //disminuir

                DB::commit();
                return response()->json(['ok' => "Pedido finalizado exitosamente"]);
            } catch (Exception $ex) {
                DB::rollBack();
                return response()->json(['err' => $ex->getMessage()]);
            }
        }
    }

    public function  recibidos()
    {

        $recibidos =
            /*Nota_pedido_detalles::with(["pedido", "stock"])
            ->where("ESTADO", "P")
            ->paginate(20);*/

            Nota_pedido_detalles::whereHas('pedido', function (Builder $query) {
                $query->where('ESTADO', '=', 'P');
            })->paginate(20);

        if (request()->ajax())
            return view("pedidos/recibidos/grill",  ['recibidos' => $recibidos]);
        else
            return view("pedidos/recibidos/index",  ['recibidos' => $recibidos]);
    }



    public  function aprobar($IDPEDIDO = NULL)
    {

        if (request()->isMethod("GET"))
            return view("pedidos.recibidos.aprobacion",   ['PEDIDO_ID' =>  $IDPEDIDO]);

        $IDPEDIDO = request()->input("PEDIDO_ID");
        //vERIFICAR TIPO DE ACCION
        //NO HAY EXISTENCIA
        //CANTIDAD DIFERENTE
        //100% APROBADO  
        $tipoDeAccion = request()->input("opcion_aprobacion");


        try {
            DB::beginTransaction();

            $PEDIDO = Nota_pedido::find($IDPEDIDO);
            $PEDIDO->RECIBIDO_POR = session("ID"); //quien recibe el pedido y lo despacha
            //dejar observacion
            //quien deja observacion

            if ($tipoDeAccion ==  "SIN_STOCK") {
                $PEDIDO->OBSERVACION = "SIN STOCK";
                $PEDIDO->ESTADO = "R"; //Rechazado
                $PEDIDO->save();
                DB::commit();
                return   response()->json(['ok' => "El pedido fue desaprobado"]);
            } else $PEDIDO->ESTADO = "A";

            $PEDIDO->save();

            //Solo dar salida si y solo si hay stock

            $autoriza = request()->input("AUTORIZADO_POR"); //quien autoriza la salida
            //Disminuir esto en Matriz
            //Detalle de productos solicitados
            $detalle_pedido =  $PEDIDO->nota_pedido_detalles;
            $ITEM =   $detalle_pedido[0]->ITEM;
            $CANTIDAD =   $detalle_pedido[0]->CANTIDAD; //Esta cantidad es editable
            //Si el despachante cambio la cantidad solicitada
            if ($tipoDeAccion == "CANTIDAD_EDITADA")
                $CANTIDAD =  Utilidades::limpiar_numero(request()->input("CANTIDAD"));

            //STOCK
            $STOCK = Stock::find($ITEM);
            //nueva salida
            $salida =  [
                'NUMERO' => '', 'FECHA' => date("Y-m-d"),
                'PEDIDO_ID' => $IDPEDIDO,
                'SUCURSAL' => session("SUCURSAL"), 'TIPO_SALIDA' => $STOCK->TIPO,
                'DESTINO' => "SUCURSAL", 'SUCURSAL_DESTINO' => $PEDIDO->SUCURSAL,
                'REGISTRADO_POR' => session("ID"),
                'AUTORIZADO_POR' =>  $autoriza,
                'CONCEPTO' => "TRASLADO DE MERCADERIAS A SUCURSAL"
            ];
            $n_salida = new Salidas();
            $n_salida->fill($salida);
            $n_salida->save();
            $salida_detalle = [
                'SALIDA_ID' => $n_salida->REGNRO,
                'ITEM' => $ITEM, 'CANTIDAD' => $CANTIDAD,  'TIPO' => $STOCK->TIPO
            ];
            $d_salida = new Salidas_detalles();
            $d_salida->fill($salida_detalle);
            $d_salida->save();

            //Actualizar existencia
            $existencia = Stock_existencias::where("SUCURSAL", session("SUCURSAL"))
                ->where("STOCK_ID",  $ITEM)->first();
            $existencia->CANTIDAD =  $existencia->CANTIDAD -  $CANTIDAD;
            $existencia->save(); //disminuir


            DB::commit();
            return   response()->json(['ok' => "El pedido fue aprobado exitosamente"]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['err' => $e->getMessage()]);
        }
    }
}

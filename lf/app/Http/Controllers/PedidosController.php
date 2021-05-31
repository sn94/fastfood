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
use App\Models\Usuario;
use App\Models\Ventas;
use App\Models\Ventas_det;
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

        $search_term=  request()->has("buscado") ?   request()->input("buscado") : "";
        $sucursal=  request()->has("sucursal") ?   request()->input("sucursal") :  session("SUCURSAL");
        $fecha=  request()->has("fecha") ?   request()->input("fecha") :  date("Y-m-d");

        // $stockActual = ($stock->ENTRADAS + $stock->ENTRADA_PE + $stock->ENTRADA_RESIDUO) - ($stock->SALIDAS + $stock->SALIDA_VENTA);
        //Pedido
        $pedido = Nota_pedido::join("nota_pedido_detalles", "nota_pedido_matriz.REGNRO",  "=", "nota_pedido_detalles.NPEDIDO_ID")
            ->join("stock", "nota_pedido_detalles.ITEM", "=", "stock.REGNRO")
            ->join("unimed", "unimed.REGNRO", "=", "stock.MEDIDA")
            ->where("nota_pedido_matriz.SUCURSAL", $sucursal)
            ->where("nota_pedido_matriz.FECHA", $fecha)
            ->whereRaw(" (stock.CODIGO LIKE '%$search_term%' or stock.BARCODE LIKE '%$search_term%' or  stock.DESCRIPCION LIKE '%$search_term%') ")
            ->select("nota_pedido_matriz.*", "nota_pedido_detalles.*", "stock.DESCRIPCION", "unimed.UNIDAD as MEDIDA")
            ->orderBy("ESTADO")
            ->with("nota_pedido_detalles");

            $formato= request()->header("formato");
            if( $formato == "json")
            return response()->json(   $pedido->get());
    
            if($formato == "pdf") {
                $fecha= date( 'd/m/Y',  strtotime( $fecha ) ) ;
                return $this->responsePdf("pedidos.realizados.grill",
                 ["PEDIDOS" => $pedido->get(),  'fecha'=>$fecha],  "Pedidos realizados el $fecha");
            }
        if (request()->ajax())
            return view("pedidos.realizados.grill",  ['PEDIDOS' => $pedido->paginate(20)]);
        else
            return view("pedidos.realizados.index",  ['PEDIDOS' => $pedido->paginate(20)]);
    }



    //LO vendido 
    public function unidades_vendidas()
    {

        $search_term=  request()->has("buscado") ?   request()->input("buscado") : "";
        $sucursal=  request()->has("sucursal") ?   request()->input("sucursal") :  session("SUCURSAL");
        $fecha=  request()->has("fecha") ?   request()->input("fecha") :  date("Y-m-d");

        $productosVendidos = Ventas::join("ventas_det", "ventas_det.VENTA_ID", "=", "ventas.REGNRO")
        ->join("stock", "stock.REGNRO", "=", "ventas_det.ITEM")
       
            ->join("unimed", "unimed.REGNRO", "=", "stock.MEDIDA")
            
            ->where("ventas.SUCURSAL", $sucursal )
            ->where("ventas.FECHA",  $fecha )
            ->where("ventas.FECHA",  $fecha )
            ->whereRaw(" (stock.CODIGO LIKE '%$search_term%' or stock.BARCODE LIKE '%$search_term%' or  stock.DESCRIPCION LIKE '%$search_term%') ")
            
 
           
            ->groupBy("ventas_det.ITEM")
            ->select("stock.*","ventas.FECHA", DB::raw("FORMAT(SUM(ventas_det.CANTIDAD), 0,'de_DE' ) as CANTIDAD_VENDIDA"),   "unimed.UNIDAD as UNIDAD_MEDIDA");
        $formato= request()->header("formato");
        if( $formato == "json")
        return response()->json(   $productosVendidos->get());

        if($formato == "pdf") {
            $fecha= date( 'd/m/Y',  strtotime(  date("Y-m-d").' - 1 days' ) ) ;
            return $this->responsePdf("pedidos.unidades_vendidas.grill",
             ["PRODUCTOS" => $productosVendidos->get(), "fecha"=>  $fecha],  "Unidades vendidas");
        }
        $productosVendidos =  $productosVendidos->paginate(20);

        if (request()->ajax())
            return view("pedidos.unidades_vendidas.grill", ['PRODUCTOS' =>  $productosVendidos]);
        else
            return view("pedidos.unidades_vendidas.index", ['PRODUCTOS' =>  $productosVendidos]);
    }






    public function create($STOCKID =  null, $VENDIDO= 0)
    {
        if (request()->isMethod("GET")) {

            //solicitado_por: nick de usuario actual
            $SOLICITADO_POR = Usuario::find( session("ID"))->NOMBRES;
            $CANTIDAD =  $VENDIDO;
            //cantidad por defecto lo vendido en el dia
            return view(
                "pedidos.forms.pedido",
                [
                    'STOCK' =>  Stock::find($STOCKID),
                    'SOLICITADO_POR' => $SOLICITADO_POR,
                    'CANTIDAD' => $CANTIDAD
                ]
            );
        }

        //Recibir cantidad
        $STOCKID =  request()->input("ITEM");
        $cantidad = request()->input("CANTIDAD");
        $solicitado_por = request()->input("SOLICITADO_POR");
        $fecha_venta = request()->input("FECHA_VENTA");
        try {
            DB::beginTransaction();
            $pedido = new Nota_pedido();
            $pedido->SUCURSAL = session("SUCURSAL");
            $pedido->FECHA = date("Y-m-d");
            $pedido->SOLICITADO_POR =  $solicitado_por;
            $pedido->REGISTRADO_POR =  session("ID");
            $pedido->FECHA_VENTA =  $fecha_venta;
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


public function editar( $PEDIDOID= NULL){

    if(   request()->isMethod("GET")){
        $NotaPedido= Nota_pedido::find( $PEDIDOID );
        $Detalle = $NotaPedido->nota_pedido_detalles;
        $Stock= Stock::find(  $Detalle[0]->ITEM );
    
        return view("pedidos.forms.pedido", ['PEDIDO'=>  $NotaPedido, 'DETALLE'=> $Detalle, 'STOCK'=> $Stock ]);
    }
    //Recibir cantidad
    $PEDIDOID =  request()->input("REGNRO");
    $STOCKID =  request()->input("ITEM");
    $cantidad = request()->input("CANTIDAD");
    $solicitado_por = request()->input("SOLICITADO_POR");
    $fecha_venta = request()->input("FECHA_VENTA");
    try {
        DB::beginTransaction();
        $pedido = Nota_pedido::find(  $PEDIDOID   );
        $pedido->SUCURSAL = session("SUCURSAL");
        
        $pedido->SOLICITADO_POR =  $solicitado_por;
        $pedido->REGISTRADO_POR =  session("ID");
        $pedido->FECHA_VENTA =  $fecha_venta;
        $pedido->CONCEPTO = "SOLICITUD DE PRODUCTOS A CASA MATRIZ";
        $pedido->ESTADO = "P";
        $pedido->save();
        //detalle
        Nota_pedido_detalles::where("NPEDIDO_ID",  $pedido->REGNRO )->delete();
        $detalle_pedido = new Nota_pedido_detalles();
        $detalle_pedido->ITEM =  $STOCKID;
        $detalle_pedido->TIPO = Stock::find($STOCKID)->TIPO;
        $detalle_pedido->CANTIDAD =  Utilidades::limpiar_numero($cantidad);
        $detalle_pedido->NPEDIDO_ID =  $pedido->REGNRO;
        $detalle_pedido->save();
        DB::commit();
        return response()->json(['ok' => "Pedido N° $pedido->REGNRO  actualizado"]);
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
            $PEDIDO->received_by = session("ID");

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

            $elDetalle =  Nota_pedido_detalles::where("NPEDIDO_ID", $IDPEDIDO)->where("ITEM", $ITEM)->first();
            $elDetalle->CANT_ACEPTADA = $CANTIDAD;
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

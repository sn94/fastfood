<?php

namespace App\Http\Controllers;

use App\Helpers\Utilidades;
use App\Http\Controllers\Controller;
use App\Models\Compras;
use App\Models\Compras_detalles;
use App\Models\Nota_pedido;
use App\Models\Stock;
use App\Models\Stock_existencias;
use Error;
use Exception;
use Illuminate\Support\Facades\DB;


class CompraController extends Controller
{

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index()
    {


        $SUCURSAL = session("SUCURSAL");
        $FECHA_DESDE = "";
        $FECHA_HASTA = "";
        $PROVEEDOR = "";
        $FORMA_PAGO = "";

        //parametros
        if (request()->isMethod("POST")) {

            $SUCURSAL =  request()->has("SUCURSAL") ?   request()->input("SUCURSAL") :  $SUCURSAL;
            $FECHA_DESDE = request()->has("FECHA_DESDE") ?   request()->input("FECHA_DESDE") :  $FECHA_DESDE;
            $FECHA_HASTA = request()->has("FECHA_HASTA") ?   request()->input("FECHA_HASTA") :  $FECHA_HASTA;
            $PROVEEDOR = request()->has("PROVEEDOR") ?   request()->input("PROVEEDOR") :  $PROVEEDOR;
            $FORMA_PAGO = request()->has("FORMA_PAGO") ?   request()->input("FORMA_PAGO") :  $FORMA_PAGO;
        }


        $COMPRAS = Compras::orderBy("created_at")
            ->where("SUCURSAL", $SUCURSAL);
        //OTROS FILTROS

        if ($FECHA_DESDE != ""  &&  $FECHA_HASTA != "")
            $COMPRAS =  $COMPRAS->where("FECHA", ">=",  $FECHA_DESDE)->where("FECHA", "<=",  $FECHA_HASTA);
        if ($PROVEEDOR != "")
            $COMPRAS =  $COMPRAS->where("PROVEEDOR",  $PROVEEDOR);
        if ($FORMA_PAGO != "")
            $COMPRAS =  $COMPRAS->where("FORMA_PAGO",  $FORMA_PAGO);


        //Content Type solicitado
        //El formato de los datos
        $formato =  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json") {
            $COMPRAS =  $COMPRAS->get();
            return response()->json($COMPRAS);
        }

        if ($formato == "pdf") {
            $COMPRAS =  $COMPRAS->get();
            return $this->responsePdf("compra.index.simple",  $COMPRAS, "Compras");
        }

        //Formato html
        $COMPRAS = $COMPRAS->paginate(20);
        if (request()->ajax())
            return view("compra.index.grill", ['COMPRAS' => $COMPRAS]);
        else
            return view('compra.index.index', ['COMPRAS' => $COMPRAS]);
    }




    private function  filtro1()
    {
        $FECHA_DESDE = "";
        $FECHA_HASTA = "";
        $FECHA_DESDE = request()->has("FECHA_DESDE") ?   request()->input("FECHA_DESDE") :  $FECHA_DESDE;
        $FECHA_HASTA = request()->has("FECHA_HASTA") ?   request()->input("FECHA_HASTA") :  $FECHA_HASTA;

        $tipo =  request()->has("TIPO_STOCK") ?  request()->input("TIPO_STOCK") : "";
        $proveedor =  request()->has("PROVEEDOR") ?  request()->input("PROVEEDOR") : "";
        $sucursal =  request()->has("SUCURSAL") ?  request()->input("SUCURSAL") : session("SUCURSAL");
        $forma_pago =  request()->has("FORMA_PAGO") ?  request()->input("FORMA_PAGO") : "";

        $COMPRAS =  [];

        try {
            //costos de producto por tipo y proveedor
            $COMPRAS =  Compras::join("compras_detalles", "compras_detalles.COMPRA_ID", "=", "compras.REGNRO")
                ->leftJoin("proveedores", "proveedores.REGNRO",  "compras.PROVEEDOR")
                ->join("stock", "stock.REGNRO", "=", "compras_detalles.ITEM")
                ->where("SUCURSAL", $sucursal)
                ->groupBy("ITEM")->groupBy("stock.REGNRO")->groupBy("PROVEEDOR")
                ->groupBy("P_UNITARIO")
                ->groupBy("PVENTA");

            if ($FECHA_DESDE != ""   &&  $FECHA_HASTA !=  "")
                $COMPRAS =  $COMPRAS->where("FECHA", ">=",  $FECHA_DESDE)->where("FECHA", "<=",  $FECHA_HASTA);

            if ($proveedor != "")
                $COMPRAS = $COMPRAS->where("compras.PROVEEDOR", $proveedor);
            if ($forma_pago != "")
                $COMPRAS = $COMPRAS->where("compras.FORMA_PAGO", $forma_pago);
            if ($tipo != "")
                $COMPRAS = $COMPRAS->where("stock.TIPO", $tipo);
            $COMPRAS = $COMPRAS->select(
                "compras.REGNRO as COMPRA_ID",
                "compras.NUMERO as FACTURA",
                "compras.SUCURSAL",
                "compras.FECHA",
                "ITEM",
                "stock.TIPO as TIPO_PRODUCTO",
                "stock.DESCRIPCION",
                DB::raw("format(stock.PVENTA, 0, 'de_DE') as PVENTA"),
                DB::raw("format(P_UNITARIO, 0, 'de_DE') as P_UNITARIO"),
                "PROVEEDOR",
                "proveedores.NOMBRE as PROVEEDOR_NOM"
            );
            return $COMPRAS;
        } catch (Error $e) {
            throw $e;
        }
    }


    private function  filtro2()
    {


        $FECHA_DESDE = "";
        $FECHA_HASTA = "";
        $FECHA_DESDE = request()->has("FECHA_DESDE") ?   request()->input("FECHA_DESDE") :  $FECHA_DESDE;
        $FECHA_HASTA = request()->has("FECHA_HASTA") ?   request()->input("FECHA_HASTA") :  $FECHA_HASTA;
        $sucursal =  request()->has("SUCURSAL") ?  request()->input("SUCURSAL") : ""; // session("SUCURSAL");
        $mas_comprado =  request()->has("MAS_COMPRADO") ?  request()->input("MAS_COMPRADO") : "CANTIDAD";

        $COMPRAS = [];

        $COMPRAS = Compras::join("compras_detalles", "compras_detalles.COMPRA_ID", "=", "compras.REGNRO")
            ->join("stock",  "stock.REGNRO", "=", "compras_detalles.ITEM")
            ->join("unimed",  "stock.MEDIDA", "=", "unimed.REGNRO");
        if ($sucursal != "")
            $COMPRAS = $COMPRAS->where("SUCURSAL",  $sucursal);

        if ($FECHA_DESDE != ""   &&  $FECHA_HASTA !=  "")
            $COMPRAS =  $COMPRAS->where("FECHA", ">=",  $FECHA_DESDE)->where("FECHA", "<=",  $FECHA_HASTA);

        $COMPRAS = $COMPRAS->select(
            "stock.*",
            "unimed.DESCRIPCION as UNI_MEDIDA",
            "SUCURSAL",
            DB::raw("count(compras_detalles.REGNRO) AS NRO_COMPRAS"),
            DB::raw("sum(compras_detalles.CANTIDAD) AS CANTIDAD")
        );
        //Agrupar y Ordenar
        //Segun nro de compras o cantidad
        $ordenamiento = "sum(compras_detalles.CANTIDAD)";
        if ($mas_comprado == "COMPRAS") //Por numero de compras
            $ordenamiento = "count(compras_detalles.REGNRO)";

        $COMPRAS =  $COMPRAS->groupBy("ITEM")
            ->groupBy("SUCURSAL")
            ->orderByRaw("$ordenamiento   DESC");
        return $COMPRAS;
    }


    public function filtrar($filterParam = NULL)
    {

        $filtro = is_null($filterParam) ?  (request()->has("FILTRO") ?  request()->input("FILTRO")  : "1")  : $filterParam;
        $nombreFiltro = "filtro$filtro";

        if (request()->isMethod("GET")  &&  request()->ajax()) {
            return view("compra.reportes.filters.filter$filtro");
        }

        $COMPRAS =  [];

        try {
            $COMPRAS =  $this->{$nombreFiltro}();
        } catch (Error $x) {
            return response()->json(['err' =>  $x->getMessage()]);
        }

        //Content Type solicitado
        //El formato de los datos
        $formato =  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json") {

            try {
                $COMPRAS =  $COMPRAS->get();
                return response()->json($COMPRAS);
            } catch (Exception $e) {
                return response()->json(['err' =>  $e->getMessage()]);
            }
        }

        if ($formato == "pdf") {
            try {
                $COMPRAS =  $COMPRAS->get();
                return $this->responsePdf("compra.reportes.views.filter$filtro",  $COMPRAS, "Compras");
            } catch (Exception $e) {
                return response()->json(['err' =>  $e->getMessage()]);
            }
        }


        //Formato html
        try {
            $COMPRAS =  is_array($COMPRAS)  ?  $COMPRAS :  $COMPRAS->paginate(20);
        } catch (Exception  $e) {
            return response()->json(['err' =>  $e->getMessage()]);
        }


        if (request()->ajax())
            return view("compra.reportes.views.filter$filtro", ['FILTRO' => $filtro, 'COMPRAS' => $COMPRAS]);
        else
            return view('compra.reportes.index', ['FILTRO' => $filtro, 'COMPRAS' => $COMPRAS]);
    }





    public  function proveedores_frecuentes()
    {
        $MES = request()->has("MES") ?   request()->input("MES") :  date("m");
        $ANIO =  request()->has("ANIO") ?   request()->input("ANIO") :  date("Y");

        $proveedores = Compras::join("proveedores", "proveedores.REGNRO", "=", "compras.PROVEEDOR")
            ->groupBy("PROVEEDOR")
            ->whereRaw('month(FECHA)',  $MES)
            ->whereRaw('year(FECHA)',  $ANIO)
            ->select("proveedores.NOMBRE as PROVEEDOR_NOMBRE",  DB::raw('count(compras.REGNRO) AS NUMERO_COMPRAS'))
            ->orderByRaw('count(compras.REGNRO) DESC')->limit(5)->get();


        $titulo = "";
        if ($MES  ==   date("m")   &&   $ANIO ==  date("Y"))
            $titulo = "PROVEEDORES MÁS FRECUENTES EN ESTE MES";
        else {
            $descriMes = Utilidades::monthDescr($MES);
            $titulo = "PROVEEDORES MÁS FRECUENTES EN $descriMes $ANIO";
        }

        return response()->json(["titulo" => $titulo,  "data" =>    $proveedores]);
    }



    public function create_unitaria()
    {

        //cantidad
        $cantidad =  Utilidades::limpiar_numero(request()->input("CANTIDAD"));
        //itemcod
        $item = request()->input("ITEM");
        //NPEDIDO
        $pedido = request()->input("NPEDIDO_ID");
        try {
            DB::beginTransaction();
            $n_compra = new Compras();
            $n_compra->SUCURSAL =  session("SUCURSAL");
            $n_compra->CONDICION = "CONTADO";
            $n_compra->FECHA = date("Y-m-d");
            $n_compra->CONCEPTO = "RECEPCIÓN DE PROD. Y MERCAD. DE MATRIZ";
            $n_compra->FORMA_PAGO = "EFECTIVO";
            $n_compra->REGISTRADO_POR = session("ID");
            $n_compra->NPEDIDO_ID = $pedido;
            $n_compra->save();
            //detalle
            $d_compra = new Compras_detalles();
            //Aditional item data
            $itemModel = Stock::find($item);
            /**Detail  */
            $d_compra->COMPRA_ID =  $n_compra->REGNRO;
            $d_compra->ITEM =  $item;
            $d_compra->CANTIDAD =  $cantidad;
            $d_compra->P_UNITARIO = $itemModel->PCOSTO;
            $d_compra->EXENTA = 0;
            $d_compra->IVA5 = $itemModel->TRIBUTO == "5" ? ($cantidad * $itemModel->PCOSTO) : 0;
            $d_compra->IVA10 = $itemModel->TRIBUTO == "10" ? ($cantidad * $itemModel->PCOSTO) : 0;
            $d_compra->TIPO = $itemModel->TIPO;
            $d_compra->save();
            //ACTUALIZAR ESTADO PEDIDO 
            $pedidoModel = Nota_pedido::find($pedido);
            $pedidoModel->ESTADO =  "F"; //Finalizado Aprobado Rechazado Pendiente
            $pedidoModel->save();
            DB::commit();
            return response()->json(['ok' => "Compra N° $n_compra->REGNRO registrada"]);
        } catch (Exception  $e) {
            DB::rollBack();
            return response()->json(['err' =>  $e->getMessage()]);
        }
    }



    public function create()
    {
        if (request()->getMethod()  ==  "GET") {

            return view(
                'compra.proceso.create'
            );
        } else {

            $Datos = request()->input();
            $CABECERA = $Datos['CABECERA'];
            $DETALLE = $Datos['DETALLE'];


            try {
                DB::beginTransaction();
                //CABECERA
                $n_compra = new Compras();
                $n_compra->fill($CABECERA);
                $n_compra->save();
                //DETALLE

                foreach ($DETALLE as $row) :
                    $datarow = $row;
                    $datarow['COMPRA_ID'] = $n_compra->REGNRO;
                    $d_compra =  new Compras_detalles();
                    $d_compra->fill($datarow);
                    $d_compra->save();

                    (new StockController())->actualizar_existencia($datarow['ITEM'], $datarow['CANTIDAD'], 'INC');


                endforeach;


                DB::commit();
                return response()->json(['ok' =>  "Entrada registrada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function update($IDCOMPRA = NULL)
    {
        if (request()->getMethod()  ==  "GET") {

            $compra = Compras::find($IDCOMPRA);
            $detalles = $compra->compras_detalle;
            return view(
                'compra.proceso.create',
                ['COMPRA' => $compra, 'DETALLE' =>  $detalles, 'EDICION' => TRUE]
            );
        } else {

            $Datos = request()->input();
            $CABECERA = $Datos['CABECERA'];
            $DETALLE = $Datos['DETALLE'];


            try {
                DB::beginTransaction();
                //CABECERA
                $n_compra = Compras::find($CABECERA['REGNRO']);
                $n_compra->fill($CABECERA);
                $n_compra->save();
                //DETALLE

                //Deshacer stock
                $d_compra =  $n_compra->compras_detalle;
                foreach ($d_compra as $datarow) (new StockController())->actualizar_existencia($datarow['ITEM'], $datarow['CANTIDAD'], 'DEC');
                //Eliminar
                Compras_detalles::where("COMPRA_ID",  $n_compra->REGNRO)->delete();

                foreach ($DETALLE as $row) :
                    $datarow = $row;
                    $datarow['COMPRA_ID'] = $n_compra->REGNRO;
                    $d_compra =  new Compras_detalles();
                    $d_compra->fill($datarow);
                    $d_compra->save();
                    (new StockController())->actualizar_existencia($row['ITEM'], $row['CANTIDAD'], 'INC');
                endforeach;


                DB::commit();
                return response()->json(['ok' =>  "Compra actualizada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function delete($IDCOMPRA = NULL)
    {

        $compra = Compras::find($IDCOMPRA);
        try {
            DB::beginTransaction();
           
                  //Deshacer stock
                  $d_compra =  $compra->compras_detalle;
                  foreach ($d_compra as $datarow) (new StockController())->actualizar_existencia($datarow['ITEM'], $datarow['CANTIDAD'], 'DEC');
                  //Eliminar
                  Compras_detalles::where("COMPRA_ID",  $compra->REGNRO)->delete();
                  if (!is_null($compra))  $compra->delete();

            //borrar detalle
            Compras_detalles::where("COMPRA_ID",  $IDCOMPRA)->delete();
            DB::commit();
            return response()->json(['ok' =>  "Borrado"]);
        } catch (Exception $ex) {
            return response()->json(['err' =>  $ex]);
        }
    }
}

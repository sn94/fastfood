<?php

namespace App\Http\Controllers;

use App\Helpers\Utilidades;
use App\Http\Controllers\Controller;
use App\Http\Requests\StockRequest;
use App\Models\Compras;
use App\Models\Nota_pedido_detalles;
use App\Models\Nota_residuos;
use App\Models\Nota_residuos_detalle;
use App\Models\PreciosVenta;
use App\Models\Receta;
use App\Models\Remision_de_terminados;
use App\Models\Salidas;
use App\Models\Stock;
use App\Models\Sucursal;
use App\Models\Ventas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\FuncCall;

class StockController extends Controller
{

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index($TIPO = "ALL") //PE
    {
        Artisan::call('storage:link'); //crea enlaces simbolicos si falta 

        $stock =  $this->buscar_productos(request(), $TIPO)->with("precios")->with("unidad_medida");
        //El formato de los datos
        $formato =  request()->header("formato");
        //Si es JSON retornar
        if ($formato == "json") {
            $stock =  $stock->get();
            return response()->json($stock);
        }

        if ($formato ==  "pdf") {
            $stock =  $stock->get();
            return $this->responsePdf("stock.grill.simple",  $stock,  "Stock");
        }

        //Formato Html
        $stock =  $stock->paginate(20);
        if (request()->ajax())
            return view('stock.grill.index',  ['TIPO' => $TIPO,  'stock' =>   $stock]);
        else
            return view('stock.index',  ['TIPO' => $TIPO]);
    }




    public function index_light()
    {
        return response()->json(Stock::findAll());
    }






    /**
     * @todo
     * Filtra por nombre de producto
     * y  Filtra por tipo:   (MP|PE|PP|AF)    o    por familia
     * y Ordena por:
     * DESCRIPCION  ASC|DESC
     * PVENTA  ASC/DESC
     */
    public function buscar_productos(Request $request, $TIPO)
    {


        /**
         * Parametros
         */
        $buscado = "";
        $tipo =  $TIPO;
        $familia = "";
        $sucursal = session("SUCURSAL");
        //parametros de orden
        $descripcion_orden =  "";
        $pventa_orden =  "";

        if ($request->method() ==  "POST") {
            $buscado =  $request->input("buscado");
            $tipo = $request->has("tipo") ?   $request->input("tipo") :   $tipo;
            $familia = $request->has("familia") ?   $request->input("familia") :   $familia;
            $sucursal = $request->has("sucursal") ?   $request->input("sucursal")  :  $sucursal;
            $descripcion_orden = $request->has("orden.DESCRIPCION") ?   $request->input("orden.DESCRIPCION")  :  $descripcion_orden;
            $pventa_orden = $request->has("orden.PVENTA") ?   $request->input("orden.PVENTA")  :  $pventa_orden;
        }

        /**
         * Ordenamiento
         */

        $columnaOrdena = "created_at";
        $valorOrdena = "DESC";

        if ($descripcion_orden != "")  $columnaOrdena = "DESCRIPCION";
        elseif ($pventa_orden != "") $columnaOrdena = "PVENTA";
        else $columnaOrdena = "created_at";

        if ($descripcion_orden != "")  $valorOrdena = $descripcion_orden;
        elseif ($pventa_orden != "") $valorOrdena = $pventa_orden;
        else $valorOrdena = "DESC";

        $stock =  Stock::orderBy($columnaOrdena,  $valorOrdena)
            ->select(
                "stock.*",
                DB::raw("if( CODIGO is NULL or CODIGO='' , stock.REGNRO, stock.CODIGO) AS CODIGO"),
                DB::raw("if( BARCODE is NULL or BARCODE='' , stock.REGNRO, stock.BARCODE) AS BARCODE")
            );
        /**Filtrar por nombre de producto */
        if ($buscado !=  "") {
            $stock =  $stock
                ->whereRaw("  CODIGO LIKE '%$buscado%' or BARCODE LIKE '%$buscado%' or  DESCRIPCION LIKE '%$buscado%'  ");
        }

        //Filtrar por preventa o preparados
        if ($tipo !=  "") {
            //Solicitud de datos para venta? Si no es por venta, permitir filtrar por un
            // solo tipo de item
            if ($tipo == "VENTA")
                $stock =  $stock->where("TIPO", "=",  "PE")->orWhere("TIPO", "=", "PP");
            elseif ($tipo == "ALL") {
                $stock = $stock->orderBy("TIPO");
                return $stock;
            } else $stock =  $stock->where("TIPO", "=",  $tipo);
        }
        //Filtrar por familia 
        if ($familia !=  "")  $stock = $stock->where("FAMILIA", $familia);

        //Recoger entradas y salidas de la sucursal actual
        //En compras y salidas

        $stock =  $stock->addSelect([
            'ENTRADAS' =>  Compras::join("compras_detalles", "compras.REGNRO", "compras_detalles.COMPRA_ID")
                ->whereColumn('compras_detalles.ITEM', 'stock.REGNRO')
                ->where("compras.SUCURSAL",  $sucursal)
                ->select(DB::raw("if(  sum(CANTIDAD)  is null, 0 , sum(CANTIDAD) ) "))
                ->limit(1),

            'SALIDAS' =>  Salidas::join("salidas_detalles", "salidas.REGNRO", "salidas_detalles.SALIDA_ID")
                ->whereColumn('salidas_detalles.ITEM', 'stock.REGNRO')
                ->where("salidas.SUCURSAL",  $sucursal)
                ->select(DB::raw("if(  sum(CANTIDAD)  is null, 0 , sum(CANTIDAD) ) "))
                ->limit(1),

            'SALIDA_VENTA' =>  Ventas::join("ventas_det", "ventas.REGNRO", "ventas_det.VENTA_ID")
                ->whereColumn('ventas_det.ITEM', 'stock.REGNRO')
                ->where("ventas.SUCURSAL",  $sucursal)
                ->select(DB::raw("if(  sum(CANTIDAD)  is null, 0 , sum(CANTIDAD) ) "))
                ->limit(1),

            'ENTRADA_PE' =>  Remision_de_terminados::join("remi_produ_terminados_detalle", "remi_produ_terminados.REGNRO", "remi_produ_terminados_detalle.REMISION_ID")
                ->whereColumn('remi_produ_terminados_detalle.ITEM', 'stock.REGNRO')
                ->where("remi_produ_terminados.SUCURSAL",  $sucursal)
                ->select(DB::raw("if(  sum(CANTIDAD)  is null, 0 , sum(CANTIDAD) ) "))
                ->limit(1),

            'ENTRADA_RESIDUO' =>  Nota_residuos::join("nota_residuos_detalle", "nota_residuos.REGNRO", "nota_residuos_detalle.NRESIDUO_ID")
                ->whereColumn('nota_residuos_detalle.ITEM', 'stock.REGNRO')
                ->where("nota_residuos.SUCURSAL",  $sucursal)
                ->select(DB::raw("if(  sum(CANTIDAD)  is null, 0 , sum(CANTIDAD) ) "))
                ->limit(1),

        ]);
        return $stock;
    }







    public function filtrar($filterParam = NULL)
    {

        $filtro = is_null($filterParam) ?  (request()->has("FILTRO") ?  request()->input("FILTRO")  : "1")  : $filterParam;

        if (request()->isMethod("GET")  &&  request()->ajax()) {
            return view("stock.reportes.filters.filter$filtro");
        }

        $STOCK =  [];
        $TITULO = "";

        // PRODUCTOS MÁS PEDIDOS POR SUCURSALES
        if ($filtro ==  "1") {
            try {

                $resultado =  $this->filtro1();
                $TITULO =  $resultado['titulo'];
                $STOCK =  $resultado['data'];
            } catch (Exception $x) {
                return response()->json(['err' =>  $x->getMessage()]);
            }
        }
        if ($filtro ==  "2") { //productos mas compra

            try {
                $resultado =  $this->filtro2();
                $TITULO =  $resultado['titulo'];
                $STOCK =  $resultado['data'];
            } catch (Exception $e) {
                return response()->json(['err' =>  $e->getMessage()]);
            }
        }


        //Content Type solicitado
        //El formato de los datos
        $formato =  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json") {

            try {
                $STOCK =  $STOCK->get();
                //Solicitan datos json incluido un titulo descriptivo
                $DESCRIPTIVO = request()->has("DESCRIPTIVO") ? request()->input("DESCRIPTIVO") : "N";
                if ($DESCRIPTIVO ==  "N")
                    return response()->json($STOCK);
                else
                    return response()->json(["titulo" =>  $TITULO, "data" => $STOCK]);
            } catch (Exception $e) {
                return response()->json(['err' =>  $e->getMessage()]);
            }
        }

        if ($formato == "pdf") {
            try {
                $STOCK =  $STOCK->get();
                return $this->responsePdf("stock.reportes.views.filter$filtro",  $STOCK, $TITULO);
            } catch (Exception $e) {
                return response()->json(['err' =>  $e->getMessage()]);
            }
        }


        //Formato html
        try {
            $STOCK =  is_array($STOCK)  ?  $STOCK :  $STOCK->paginate(15);
        } catch (Exception  $e) {
            return response()->json(['err' =>  $e->getMessage()]);
        }


        if (request()->ajax())
            return view("stock.reportes.views.filter$filtro", ['FILTRO' => $filtro, 'STOCK' => $STOCK]);
        else
            return view('stock.reportes.index', ['FILTRO' => $filtro, 'STOCK' => $STOCK]);
    }


    /**
     * 
     * 
     * 
     * Busquedas especificas
     */

    //PRODUCTOS MÁS PEDIDOS POR SUCURSALES
    /*
    params:  SUCURSAL_n_1   SUCURSAL  MES   ANIO

    */
    public function filtro1()
    {

        //Filtro sucursal
        $sucursal =   $sucursal =   request()->has("SUCURSAL") ? request()->input("SUCURSAL") : "";
        //Filtro mes y anio
        $mes =   request()->has("MES") ? request()->input("MES") : date("m");
        $anio = request()->has("ANIO") ? request()->input("ANIO") : date("Y");
        $FECHA_DESDE = request()->has("FECHA_DESDE") ?   request()->input("FECHA_DESDE") :  "";
        $FECHA_HASTA = request()->has("FECHA_HASTA") ?   request()->input("FECHA_HASTA") :  "";
        $TIPO_PRODUCTO =   request()->has("TIPO_STOCK") ? request()->input("TIPO_STOCK") :  ""; //TIPO DE PRODUCTO

        $loMasPedido = Nota_pedido_detalles::join("nota_pedido_matriz",  "nota_pedido_matriz.REGNRO", "=", "nota_pedido_detalles.NPEDIDO_ID")
            ->join("stock", "stock.REGNRO", "=", "nota_pedido_detalles.ITEM")
            ->join("sucursal", "sucursal.REGNRO", "=", "nota_pedido_matriz.SUCURSAL");
        //Solo filtrar por sucursal, si este parametro no se definio
        if ($sucursal != "")
            $loMasPedido = $loMasPedido->where("nota_pedido_matriz.SUCURSAL", $sucursal);

        //Filtrar por mes y anio
        if ($FECHA_DESDE == ""  &&  $FECHA_HASTA == "")
            $loMasPedido = $loMasPedido->whereRaw("month(nota_pedido_matriz.FECHA)", $mes)
                ->whereRaw("year(nota_pedido_matriz.FECHA)", $anio);
        else
            $loMasPedido = $loMasPedido->where("nota_pedido_matriz.FECHA", $FECHA_DESDE)
                ->whereRaw("nota_pedido_matriz.FECHA", $FECHA_HASTA);

        //Filtrar por tipo PRODUCTO
        if ($TIPO_PRODUCTO !=  "")
            $loMasPedido = $loMasPedido->where("stock.TIPO", $TIPO_PRODUCTO);

        //Agrupar por uno o mas productos para cada sucursal 
        //Mostrar todos los productos pedidos por sucursal
        $loMasPedido = $loMasPedido->groupBy("nota_pedido_detalles.ITEM")
            ->groupBy("nota_pedido_matriz.SUCURSAL");


        $loMasPedido = $loMasPedido->select(
            "sucursal.REGNRO AS SUCURSAL_ID",
            "sucursal.DESCRIPCION AS SUCURSAL",
            "stock.DESCRIPCION",
            DB::raw("count(nota_pedido_detalles.REGNRO) AS NUMERO_PEDIDOS")
        );

        //Redaccion de titulo
        $sucursalModel = Sucursal::find($sucursal);
        $descripcionSucursal = "";
        if (is_null($sucursalModel))  $descripcionSucursal = "LAS SUCURSALES";
        else   $descripcionSucursal = $sucursalModel->MATRIZ == "S" ? "CASA CENTRAL" : "SUC. $sucursalModel->DESCRIPCION";

        //Mes y Anio
        $mesDescripcion = Utilidades::monthDescr($mes);
        //Response
        $titulo = "LOS PRODUCTOS MÁS PEDIDOS EN $descripcionSucursal EN $mesDescripcion $anio ";
        return  ['titulo' => $titulo, "data" =>  $loMasPedido];
    }


    //Los que dejan mas residuos
    public function filtro2()
    {

        //Filtro sucursal
        $sucursal =   $sucursal =   request()->has("SUCURSAL") ? request()->input("SUCURSAL") : session("SUCURSAL");
        //Filtro mes y anio
        $mes =   request()->has("MES") ? request()->input("MES") : date("m");
        $anio = request()->has("ANIO") ? request()->input("ANIO") : date("Y");
        $FECHA_DESDE = request()->has("FECHA_DESDE") ?   request()->input("FECHA_DESDE") :  "";
        $FECHA_HASTA = request()->has("FECHA_HASTA") ?   request()->input("FECHA_HASTA") :  "";

        $residuos = Nota_residuos_detalle::join("nota_residuos", "nota_residuos.REGNRO", "=", "nota_residuos_detalle.NRESIDUO_ID")
            ->join("stock", "stock.REGNRO", "=", "nota_residuos_detalle.ITEM")
            ->where("SUCURSAL", $sucursal);

        if ($FECHA_DESDE != ""  &&  $FECHA_HASTA != "")
            $residuos =  $residuos->where("FECHA", ">=", $FECHA_DESDE)->where("FECHA", "<=", $FECHA_HASTA);
        else
            $residuos = $residuos->whereRaw("month(nota_residuos.FECHA)", $mes)
                ->whereRaw("year(nota_residuos.FECHA)", $anio);

        $residuos = $residuos->groupBy("nota_residuos_detalle.ITEM")
        ->select("nota_residuos.SUCURSAL", "stock.DESCRIPCION", "stock.TIPO", DB::raw("count(nota_residuos.REGNRO) AS NUMERO_RESIDUOS"));
        //Mes y Anio
        $mesDescripcion = Utilidades::monthDescr($mes);
        //Response
        $titulo = "SUCURSAL: $sucursal, RESIDUOS DE INGREDIENTES, $mesDescripcion $anio ";

        return ['titulo' => $titulo, 'data' => $residuos];
    }




    private function  codigo_redundante($CODIGO,  $BARCODE, $IDSTOCK = NULL)
    {
        $igualesEnCodigo = 0;
        $igualesEnCodigoDeBarra = 0;
        if ($CODIGO !=  "")
            $igualesEnCodigo = Stock::where("CODIGO", $CODIGO)->count();
        if ($BARCODE != "")
            $igualesEnCodigoDeBarra = Stock::where("BARCODE", $BARCODE)->count();

        if (($igualesEnCodigo + $igualesEnCodigoDeBarra) > 0) {

            if (is_null($IDSTOCK)) return true;
            else {
                $stock = Stock::find($IDSTOCK);
                return  !($stock->CODIGO == $CODIGO && $stock->BARCODE == $BARCODE);
            }
        } else false;
    }


    private function  nombre_redundante($NOMBRE, $IDSTOCK = NULL)
    {

        $rows =  Stock::where("DESCRIPCION", $NOMBRE)->count();
        if ($rows > 0) {
            if (is_null($IDSTOCK)) return true;
            else {
                $stock = Stock::find($IDSTOCK);
                return  !($stock->DESCRIPCION == $NOMBRE);
            }
        }
        return false;
    }



    private function create_recipe(StockRequest  $request, $stock_id)
    {
        Receta::where("STOCK_ID", $stock_id)->delete();
        if (!$request->has(["MPRIMA_ID", "CANTIDAD", "MEDIDA"]))  return;

        $mp_id = $request->input("MPRIMA_ID");
        $cantidad = $request->input("CANTIDAD");
        $medida = $request->input("MEDIDA_");

        if (!is_array($mp_id)) return;

        for ($m = 0; $m <  sizeof($mp_id); $m++) {
            $Receta_det =  new Receta();
            $Receta_det->fill(
                [
                    'STOCK_ID' => $stock_id,
                    'MPRIMA_ID' => $mp_id[$m],
                    'CANTIDAD' => $cantidad[$m],
                    'MEDIDA_' =>  $medida[$m]
                ]
            );
            $Receta_det->save();
        }
    }


    private function save_photo(StockRequest  $request, $stock_id)
    {
        $this->delete_photo($stock_id);
        $fileinst = $request->file('IMG');
        if (is_null($fileinst)) return "";
        $path = "";
        if (!is_null($fileinst))
            $path = $fileinst->store(
                'foodimg',
                'public'
            );
        return $path;
    }

    private function create_prices(StockRequest  $request, $stock_id)
    {
        PreciosVenta::where("STOCK_ID", $stock_id)->delete();

        $descripcion = $request->input("PRECIO_DESCR");
        $precio_entero = $request->input("PRECIO_ENTERO");
        $precio_mitad = $request->input("PRECIO_MITAD");
        $precio_porcion = $request->input("PRECIO_PORCION");

        if (!is_array($descripcion)) return;
        for ($m = 0; $m <  sizeof($descripcion); $m++) {

            $preciosv =  new PreciosVenta();
            $preciosv->fill(
                [
                    'STOCK_ID' =>  $stock_id,
                    'DESCRIPCION' => $descripcion[$m],
                    'ENTERO' => $precio_entero[$m],
                    'MITAD' => $precio_mitad[$m],
                    'PORCION' =>  $precio_porcion[$m]
                ]
            );
            $preciosv->save();
        }
    }

    public function create(StockRequest $request)
    {
        if ($request->getMethod()  ==  "GET") {

            if (request()->ajax())
                return view('stock.create.index');
            else
                return view('stock.create.index');
        } else {


            //artisan
            Artisan::call('storage:link');
            /*** */

            try {
                $data = $request->input();


                if ($this->codigo_redundante($data['CODIGO'],  $data['BARCODE']))
                    return response()->json(['err' => 'EL CODIGO YA EXISTE']);

                if ($this->nombre_redundante($data['DESCRIPCION']))
                    return response()->json(['err' => 'EL NOMBRE DE PRODUCTO YA EXISTE']);
                //    var_dump(  $data ); 
                //  return;

                DB::beginTransaction();

                $nuevo_stock =  new Stock();
                // var_dump($data); return;

                $nuevo_stock->fill($data);
                $nuevo_stock->save();
                if ($nuevo_stock->CODIGO == "")  $nuevo_stock->CODIGO =  $nuevo_stock->REGNRO;
                if ($nuevo_stock->BARCODE == "")  $nuevo_stock->BARCODE =  $nuevo_stock->REGNRO;
                $nuevo_stock->save();
                $stock_id =  $nuevo_stock->REGNRO;
                //Detalle receta
                if ($request->has(["MPRIMA_ID", "CANTIDAD", "MEDIDA"])) {
                    //Procesar detalle

                    $this->create_recipe($request, $stock_id);
                }
                //foto
                $path =    $this->save_photo($request, $stock_id);
                $nuevo_stock->IMG = "lf/public/images/$path"; //lf/public/
                $nuevo_stock->save();

                //Detalle precios
                $this->create_prices($request, $stock_id);
                DB::commit();
                return response()->json(['ok' =>  $nuevo_stock]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function update(StockRequest $request, $id = NULL)
    {

        if ($request->getMethod()  ==  "GET") {
            $Stock__ =  Stock::find($id);
            //Obtener receta
            $RECETA =   $Stock__->receta;
            //Precios Venta
            $PRECIOS = $Stock__->precios;

            return view('stock.create',  ['stock' =>  $Stock__,   'RECETA' =>   $RECETA, 'DETALLE_PRECIOS' => $PRECIOS]);
        } else {


            $data = $request->input();

            $IDSTOCK = $data['REGNRO'];
            if ($this->codigo_redundante($data['CODIGO'],  $data['BARCODE'], $IDSTOCK))
                return response()->json(['err' => 'EL CODIGO YA EXISTE']);

            if ($this->nombre_redundante($data['DESCRIPCION'], $IDSTOCK))
                return response()->json(['err' => 'EL NOMBRE DE PRODUCTO YA EXISTE']);

            //artisan
            Artisan::call('storage:link');
            /*** */
            try {
                $id_ = $request->input("REGNRO");
                DB::beginTransaction();
                $nuevo_stock =  Stock::find($id_);
                $nuevo_stock->fill($data);

                $stock_id =  $nuevo_stock->REGNRO;
                //Detalle receta
                $this->create_recipe($request, $stock_id);
                //Detalle precios
                $this->create_prices($request, $stock_id);
                //foto
                $path =  $this->save_photo($request, $stock_id);
                $nuevo_stock->IMG = "lf/public/images/" . $path;
                $nuevo_stock->save();

                DB::commit();
                return response()->json(['ok' =>  "Stock Actualizado"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }


    public function delete_photo($stock_id)
    {
        $reg = Stock::find($stock_id);
        //Borrar foto Si existe
        $segmentosRutaFoto = explode("/",  $reg->IMG);
        try {
            if (sizeof($segmentosRutaFoto) > 0) {

                $nombreFoto =  $segmentosRutaFoto[3] . "/" . $segmentosRutaFoto[4];
                $subruta = "public/$nombreFoto";
                Storage::delete($subruta);
                return $subruta;
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function delete($id = NULL)
    {
        $reg = Stock::find($id);
        if (!is_null($reg)) {
            DB::beginTransaction();
            try {
                $this->delete_photo($id);

                $reg->delete();
                Receta::where("STOCK_ID",  $id)->delete();
                PreciosVenta::where("STOCK_ID",  $id)->delete();

                DB::commit();
            } catch (Exception $ex) {
                DB::rollBack();
            }
            return response()->json(['ok' =>  "Stock eliminado"]);
        } else {
            return response()->json(['err' =>  "ID inexistente"]);
        }
    }



    public function   get($id = NULL)
    {
        $reg = Stock::find($id);
        if (!is_null($reg)) {

            return response()->json(['ok' =>  $reg]);
        } else {
            return response()->json(['err' =>  "ID inexistente"]);
        }
    }
}

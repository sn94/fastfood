<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockRequest;
use App\Models\Compras;
use App\Models\Nota_residuos;
use App\Models\PreciosVenta;
use App\Models\Receta;
use App\Models\Remision_de_terminados;
use App\Models\Salidas;
use App\Models\Stock;
use App\Models\Ventas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
 

class StockController extends Controller
{

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index($TIPO = "PE")
    {
        Artisan::call('storage:link'); //crea enlaces simbolicos si falta 

        $stock =  $this->buscar_productos(request(), $TIPO);


        //El formato de los datos
        $formato =  request()->header("formato");
        //Si es JSON retornar
        if ($formato == "json") {
            $stock =  $stock->with("precios")->get();
            return response()->json($stock);
        }

        if ($formato ==  "pdf") {
            $stock =  $stock->with("precios")->get();
            return $this->responsePdf("stock.grill.simple",  $stock,  "Stock");
        }

        //Formato Html
        $stock =  $stock->paginate(20);
        if (request()->ajax())
            return view('stock.grill.grill',  ['TIPO' => $TIPO,  'stock' =>   $stock]);
        else
            return view('stock.index',  ['TIPO' => $TIPO]);
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

         $columnaOrdena= "created_at";
         $valorOrdena= "DESC";

         if(  $descripcion_orden != "")  $columnaOrdena = "DESCRIPCION";
         elseif( $pventa_orden != "") $columnaOrdena= "PVENTA";
         else $columnaOrdena= "created_at";

         if(  $descripcion_orden != "")  $valorOrdena = $descripcion_orden;
         elseif( $pventa_orden != "") $valorOrdena= $pventa_orden;
         else $valorOrdena= "DESC";

        $stock =  Stock::
        orderBy(  $columnaOrdena  ,  $valorOrdena)  
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
            //Solicitud de datos para venta? Si no es por venta, permitir filtrar por un solo tipo de item
            if ($tipo == "VENTA")   $stock =  $stock->where("TIPO", "=",  "PE")->orWhere("TIPO", "=", "PP");
            else   $stock =  $stock->where("TIPO", "=",  $tipo);
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
                return view('stock.create_ajax');
            else
                return view('stock.create');
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
                return response()->json(['ok' =>  "Stock registrado"]);
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

            return view('stock.update',  ['stock' =>  $Stock__,   'RECETA' =>   $RECETA, 'DETALLE_PRECIOS' => $PRECIOS]);
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

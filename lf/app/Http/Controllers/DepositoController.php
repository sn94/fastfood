<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\Compras;
use App\Models\Compras_detalles;
use App\Models\Ficha_produccion;
use App\Models\Ficha_produccion_detalles;
use App\Models\Materiaprima;

use App\Models\Nota_residuos;
use App\Models\Nota_residuos_detalle;
use App\Models\Productos;
use App\Models\Recepcion;
use App\Models\Recepcion_detalles;
use App\Models\Remision_de_terminados;
use App\Models\Remision_de_terminados_detalle;
use App\Models\Salidas;
use App\Models\Salidas_detalles;
use App\Models\Stock;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DepositoController extends Controller
{

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index($CONTEXTO = "M")
    {

        if (request()->ajax()) {
            //return view('deposito.grill', ['contexto' =>  $contexto,  'registros' => $registro->get()])


            $buscado = "";
            if (request()->method() ==  "POST")  $buscado =  request()->input("buscado");
            //Buscar producto terminado o materia prima
            $registro = null;
            if ($CONTEXTO == "M")  $registro =  Materiaprima::orderBy("created_at");
            else  $registro =  Stock::orderBy("created_at")->where("TIPO", "P"); //preventa


            /**
             *  ENTRADAS Y salidas
             * 
             * */
            if ($CONTEXTO == "M") { //Materia prima
                $registro =  $registro->select(
                    "materia_prima.REGNRO",
                    "DESCRIPCION",
                    "STOCK_MIN",
                    "STOCK_MAX",
                    DB::raw("ifnull((select sum(CANTIDAD) from compras_detalles where compras_detalles.ITEM = materia_prima.REGNRO ), 0) as entradas"),
                    DB::raw("ifnull((select sum(CANTIDAD) from salidas_detalles where salidas_detalles.ITEM= materia_prima.REGNRO), 0) as salidas")
                );
            } else {
                //Producto
                $registro =
                    $registro =  $registro->select(
                        "productos.REGNRO",
                        "DESCRIPCION",
                        "STOCK_MIN",
                        "STOCK_MAX",
                        DB::raw("ifnull((select sum(CANTIDAD) from compras_detalles where compras_detalles.ITEM = productos.REGNRO ), 0) as entradas"),
                        DB::raw("(  ifnull((select sum(CANTIDAD) from salidas_detalles where salidas_detalles.ITEM= productos.REGNRO), 0)
                    +
                    ifnull((select sum(CANTIDAD) from ventas_det WHERE ventas_det.ITEM= productos.REGNRO  ), 0) ) as salidas")
                    );
            }


            if ($buscado !=  "") {
                $registro =  $registro
                    ->whereRaw("  DESCRIPCION LIKE '%$buscado%'  ");
            }

            return view('deposito.grill',  ['CONTEXTO' => $CONTEXTO,  'registros' =>   $registro->paginate(10)]);
        }
        return view('deposito.index');
    }






    public function recepcion()
    {
        if (request()->getMethod()  ==  "GET") {

            return view(
                'deposito.recepcion.index'
            );
        } else {

            $Datos = request()->input();

            $CABECERA = $Datos['CABECERA'];
            $DETALLE = $Datos['DETALLE'];

            try {
                DB::beginTransaction();
                //CABECERA
                $n_compra = new Recepcion();
                $n_compra->fill($CABECERA);
                $n_compra->save();
                //DETALLE

                foreach ($DETALLE as $row) :
                    $datarow = $row;
                    $datarow['RECEPCION_ID'] = $n_compra->REGNRO;

                    $d_compra =  new Recepcion_detalles();
                    $d_compra->fill($datarow);
                    $d_compra->save();
                endforeach;


                DB::commit();
                return response()->json(['ok' =>  "Entrada registrada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function compra()
    {
        if (request()->getMethod()  ==  "GET") {

            return view(
                'deposito.compra.proceso.index'
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
                endforeach;


                DB::commit();
                return response()->json(['ok' =>  "Entrada registrada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    private function get_ficha_produccion($PRODUCCIONID)
    {
        $HEADER =  Ficha_produccion::find($PRODUCCIONID);
        $DETAIL =  Ficha_produccion_detalles::join("stock", "stock.REGNRO", "ficha_produccion_detalles.ITEM")
            ->select("stock.DESCRIPCION",   "ficha_produccion_detalles.*")
            ->where("PRODUCCION_ID",  $PRODUCCIONID)->get();
        return response()->json(['CABECERA' =>  $HEADER, 'DETALLE' =>  $DETAIL]);
    }


    public function ficha_produccion($PRODUCCIONID = null)
    {
        if (request()->getMethod()  ==  "GET") {

            if (is_null($PRODUCCIONID))
                return view('deposito.ficha_produccion.create');
            else {
                $FORMATO =  request()->header("formato", "html");
                if ($FORMATO  == "html") {
                    $PRODUCCION = Ficha_produccion::find($PRODUCCIONID);
                    return view('deposito.ficha_produccion.create',  ['PRODUCCION' => $PRODUCCION, 'PRODUCCION_ID' =>   $PRODUCCIONID]);
                } elseif ($FORMATO ==  "json") {
                    return $this->get_ficha_produccion($PRODUCCIONID);
                }
            }
        } else {

            $Datos = request()->input();

            $CABECERA = $Datos['CABECERA'];
            $DETALLE_PRODUCTOS = $Datos['PRODUCTOS'];
            $DETALLE_INGREDIEN = $Datos['INGREDIENTES'];
            $DETALLE_INSUMOS = $Datos['INSUMOS'];


            try {
                $ANTI_SPOOFING_METHOD = isset($CABECERA['_method']) ?  $CABECERA['_method'] : "POST";

                DB::beginTransaction();
                //CABECERA
                $n_compra =  ($ANTI_SPOOFING_METHOD ==  "POST") ? (new Ficha_produccion()) : (Ficha_produccion::find($Datos['CABECERA']['REGNRO']));
                $n_compra->fill($CABECERA);
                $n_compra->save();

                if ($ANTI_SPOOFING_METHOD ==  "PUT") {
                    //Borrar detalles
                    Ficha_produccion_detalles::where("PRODUCCION_ID",  $Datos['CABECERA']['REGNRO'])->delete();
                }
                //DETALLE
                foreach ($DETALLE_PRODUCTOS as $row) :
                    $datarow = $row;
                    $datarow['PRODUCCION_ID'] = $n_compra->REGNRO;

                    $d_compra =  new Ficha_produccion_detalles();
                    $d_compra->fill($datarow);
                    $d_compra->save();
                endforeach;
                foreach ($DETALLE_INGREDIEN as $row) :
                    $datarow = $row;
                    $datarow['PRODUCCION_ID'] = $n_compra->REGNRO;

                    $d_compra =  new Ficha_produccion_detalles();
                    $d_compra->fill($datarow);
                    $d_compra->save();
                endforeach;

                foreach ($DETALLE_INSUMOS as $row) :
                    $datarow = $row;
                    $datarow['PRODUCCION_ID'] = $n_compra->REGNRO;

                    $d_compra =  new Ficha_produccion_detalles();
                    $d_compra->fill($datarow);
                    $d_compra->save();
                endforeach;

                DB::commit();
                return response()->json(['ok' =>  "Ficha de produccion registrada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }




    public function salida($PRODUCCIONID = null)
    {
        if (request()->getMethod()  ==  "GET") {


            if (is_null($PRODUCCIONID))  return view('salida.create');

            $PROD = Ficha_produccion::find($PRODUCCIONID);
            return view(
                'salida.create',
                [
                    'PRODUCCION_ID' =>   $PRODUCCIONID,
                    'PRODUCCION_DETALLE' =>   $PROD->detalle_produccion

                ]
            );
        } else {

            $Datos = request()->input();
            $CABECERA = $Datos['CABECERA'];


            $DETALLE = $Datos['DETALLE'];
 

            try {
                DB::beginTransaction();
                //CABECERA
                $n_salida = new Salidas();
                $n_salida->fill($CABECERA);
                $n_salida->save();
                //DETALLE

                foreach ($DETALLE as $row) :
                    $datarow = $row;
                    $datarow['SALIDA_ID'] = $n_salida->REGNRO;
                    $d_salida =  new Salidas_detalles();
                    $d_salida->fill($datarow);
                    $d_salida->save();
                endforeach;


                //Despachado
                if( isset( $CABECERA['PRODUCCION_ID'] )){
                    Ficha_produccion::find($CABECERA['PRODUCCION_ID'])
                    ->fill(["ESTADO" => "DESPACHADO", "RECIBIDO_POR" => session("ID")])->save();

                }
              
                DB::commit();
                return response()->json(['ok' =>  "Salida registrada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }





    public function nota_residuos($PRODUCCIONID =  NULL)
    {
        if (request()->getMethod()  ==  "GET") {


            if (is_null($PRODUCCIONID))
                return view('deposito.nota_residuos.index');
            else {
                $produccion = Ficha_produccion::find($PRODUCCIONID);
                return view('deposito.nota_residuos.index', ["PRODUCCION" =>  $produccion]);
            }
        } else {

            $Datos = request()->input();

            $CABECERA = $Datos['CABECERA'];
            $DETALLE = $Datos['DETALLE'];

            try {
                DB::beginTransaction();
                //CABECERA
                $n_compra = new Nota_residuos();
                $n_compra->fill($CABECERA);
                $n_compra->save();
                //DETALLE

                foreach ($DETALLE as $row) :
                    $datarow = $row;
                    $datarow['NRESIDUO_ID'] = $n_compra->REGNRO;

                    $d_compra =  new Nota_residuos_detalle();
                    $d_compra->fill($datarow);
                    $d_compra->save();
                endforeach;


                DB::commit();
                return response()->json(['ok' =>  "Nota de residuos registrada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }





    public function remision_de_terminados($PRODUCCIONID =  NULL)
    {
        if (request()->getMethod()  ==  "GET") {


            if (is_null($PRODUCCIONID))
                return view('deposito.remision_de_terminados.index');
            else {
                $produccion = Ficha_produccion::find($PRODUCCIONID);

                $pro_detalle = $produccion->detalle_produccion;
              
                return view('deposito.remision_de_terminados.index', ["PRODUCCION" =>  $produccion, 'PRODUCCION_DETALLE'=> $pro_detalle ]);
            }
        } else {

            $Datos = request()->input();

            $CABECERA = $Datos['CABECERA'];
            $DETALLE = $Datos['DETALLE'];

            try {
                DB::beginTransaction();
                //CABECERA
                $n_compra = new Remision_de_terminados();
                $n_compra->fill($CABECERA);
                $n_compra->save();
                //DETALLE

                foreach ($DETALLE as $row) :
                    $datarow = $row;
                    $datarow['REMISION_ID'] = $n_compra->REGNRO;

                    $d_compra =  new Remision_de_terminados_detalle();
                    $d_compra->fill($datarow);
                    $d_compra->save();
                endforeach;

                //Actualizar estado
                //Listo, cumplimentado
                Ficha_produccion::find($CABECERA['PRODUCCION_ID'])
                    ->fill(["ESTADO" => "LISTO", "FINALIZADO_POR" => session("ID")])->save();
                DB::commit();
                return response()->json(['ok' =>  "Remisión registrada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }






    public function update($id = NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Cargo::find($id);
            return view('cargo.update',  ['cargo' =>  $cli]);
        } else {


            //artisan
            Artisan::call('storage:link');
            /*** */
            try {
                $id_ = request()->input("REGNRO");
                DB::beginTransaction();
                $nuevo_producto =  Cargo::find($id_);
                $nuevo_producto->fill(request()->input());

                $nuevo_producto->save();
                DB::commit();
                return response()->json(['ok' =>  "Actualizado"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function delete($id = NULL)
    {
        $reg = Cargo::find($id);
        if (!is_null($reg)) {
            $reg->delete();
            return response()->json(['ok' =>  "Eliminado"]);
        } else {
            return response()->json(['err' =>  "ID inexistente"]);
        }
    }
}

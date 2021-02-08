<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\Compras;
use App\Models\Compras_detalles;
use App\Models\Materiaprima;
use App\Models\Productos;
use App\Models\Salidas;
use App\Models\Salidas_detalles;
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
            //return view('mod_admin.deposito.grill', ['contexto' =>  $contexto,  'registros' => $registro->get()])


            $buscado = "";
            if (request()->method() ==  "POST")  $buscado =  request()->input("buscado");
            //Buscar producto terminado o materia prima
            $registro = null;
            if ($CONTEXTO == "M")  $registro =  Materiaprima::orderBy("created_at");
            else  $registro =  Productos::orderBy("created_at")->where("TIPO", "P"); //preventa


            /**
             *  ENTRADAS Y salidas
             * 
             * */
            if ($CONTEXTO == "M") { //Materia prima
                $registro =  $registro->select( "materia_prima.REGNRO", "DESCRIPCION", "STOCK_MIN", "STOCK_MAX",
                    DB::raw("ifnull((select sum(CANTIDAD) from compras_detalles where compras_detalles.ITEM = materia_prima.REGNRO ), 0) as entradas"),
                    DB::raw("ifnull((select sum(CANTIDAD) from salidas_detalles where salidas_detalles.ITEM= materia_prima.REGNRO), 0) as salidas")
                );
            } else {
                //Producto
                $registro =
                    $registro =  $registro->select( "productos.REGNRO", "DESCRIPCION", "STOCK_MIN", "STOCK_MAX",
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
 
            return view('mod_admin.deposito.grill',  [  'CONTEXTO'=>$CONTEXTO,  'registros' =>   $registro->paginate(10)]);
        }
        return view('mod_admin.deposito.index');
    }






    public function recepcion($CONTEXTO)
    {
        if (request()->getMethod()  ==  "GET") {

            $resource_url = url("productos/buscar");
            $resource_url_item = url("productos/get");

            if ($CONTEXTO == "M") {
                $resource_url = url("materia-prima/buscar");
                $resource_url_item = url("materia-prima/get");
            }
            //URL PARA REGISTRAR LA COMPRA
            $POST_COMPRA_URL = url("deposito-entrada/$CONTEXTO");
            //titulo de pantalla
            $TITULO =  $CONTEXTO == "M" ? "Materia prima" : "Productos";
            return view(
                'mod_admin.deposito.entrada.recepcion',
                ['TITULO' => $TITULO, 'FORM_URL' => $POST_COMPRA_URL, 'RESOURCE_URL' => $resource_url,  'RESOURCE_URL_ITEM' =>  $resource_url_item]
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
                    $datarow['TIPO'] = $CONTEXTO;

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



    public function compra($CONTEXTO = null)
    {
        if (request()->getMethod()  ==  "GET") {

            return view(
                'mod_admin.deposito.entrada.compra',
                ['CONTEXTO' => $CONTEXTO]
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
                    $datarow['TIPO'] = $CABECERA['TIPO'];
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


    public function salida($CONTEXTO = null)
    {
        if (request()->getMethod()  ==  "GET") {


            return view(
                'mod_admin.deposito.salida',
                ['CONTEXTO' => $CONTEXTO]
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
                    $datarow['TIPO'] = $CABECERA['TIPO'];
                    $d_salida =  new Salidas_detalles();
                    $d_salida->fill($datarow);
                    $d_salida->save();
                endforeach;


                DB::commit();
                return response()->json(['ok' =>  "Salida registrada"]);
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
            return view('mod_admin.cargo.update',  ['cargo' =>  $cli]);
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

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

class SalidaController extends Controller
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









    public function create($PRODUCCIONID = null)
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
                if (isset($CABECERA['PRODUCCION_ID'])) {
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
}

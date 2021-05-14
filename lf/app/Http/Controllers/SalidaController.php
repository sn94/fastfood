<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ficha_produccion; 

use App\Models\Salidas;
use App\Models\Salidas_detalles; 
use App\Models\Stock_existencias;
use Exception;
use Illuminate\Support\Facades\DB;

class SalidaController extends Controller
{
 
 

    public function create($PRODUCCIONID = null)
    {
        if (request()->getMethod()  ==  "GET") {

            if (is_null($PRODUCCIONID))  return view('salida.create.index');

            $PROD = Ficha_produccion::find($PRODUCCIONID);
            return view(
                'salida.create.index',
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
                    //Actualizar existencia
                    $existencia = Stock_existencias::where("SUCURSAL", session("SUCURSAL"))
                        ->where("STOCK_ID",  $datarow['ITEM'])->first();
                    $existencia->CANTIDAD =  $existencia->CANTIDAD -  $datarow['CANTIDAD'];
                    $existencia->save(); //disminuir

                endforeach;



                //Cambiar estado de ficha de produccion a Despachado
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

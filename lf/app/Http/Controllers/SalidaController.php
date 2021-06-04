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



    public function index()
    {

        $sucursal = session("SUCURSAL");
        $fecha_desde =  request()->has("FECHA_DESDE") ?  request()->input("FECHA_DESDE")  : null;
        $fecha_hasta =  request()->has("FECHA_HASTA") ?  request()->input("FECHA_HASTA")  : null;

        $listaSalidas =  Salidas::where("SUCURSAL", $sucursal);

        if (!(is_null($fecha_desde))     &&    !(is_null($fecha_hasta)))
            $listaSalidas = $listaSalidas->where("FECHA", ">=",  $fecha_desde)->where("FECHA", "<=",  $fecha_hasta);

        //El formato de los datos
        $formato =  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json") return response()->json($listaSalidas->get());
        if ($formato == "pdf")
            return $this->responsePdf(
                "salida.index.grill",
                ['SALIDAS' => $listaSalidas->get()],
                "Salida de productos y materia prima"
            );


        $listaSalidas = $listaSalidas->orderBy("created_at", "DESC")->paginate(20);

        if (request()->ajax())
            return view("salida.index.grill", ['SALIDAS' =>  $listaSalidas]);
        else
            return view("salida.index.index", ['SALIDAS' =>  $listaSalidas]);
    }

    public function create($PRODUCCIONID = null)
    {
        if (request()->getMethod()  ==  "GET") {

            if (is_null($PRODUCCIONID))  return view('salida.create.index');
            /**Obtener datos de la ficha de produccion cuando se indique */
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
                    (new StockController())->actualizar_existencia(  $datarow['ITEM'],  $datarow['CANTIDAD'], 'DEC');

                endforeach;



                //Cambiar estado de ficha de produccion a Despachado (Si se ha indicado un numero de Ficha de prod.)
                if (isset($CABECERA['PRODUCCION_ID'])   &&  $CABECERA['PRODUCCION_ID'] != "") {
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






    public function update($ID = null)
    {
        if (request()->getMethod()  ==  "GET") {


            /**Obtener datos de la ficha de produccion cuando se indique */
            $SALIDA = Salidas::find($ID);
            $DETALLE = $SALIDA->salidas_detalle;
            return view(
                'salida.create.index',
                [
                    'SALIDA' =>   $SALIDA,
                    'DETALLE' =>   $DETALLE
                ]
            );
        } else {

            $Datos = request()->input();
            $CABECERA = $Datos['CABECERA'];
            $DETALLE = $Datos['DETALLE'];
            try {
                DB::beginTransaction();
                //CABECERA
                $n_salida =  Salidas::find($CABECERA['REGNRO']);
                $n_salida->fill($CABECERA);
                $n_salida->save();
                //DETALLE

                $detalleSalida= Salidas_detalles::where("SALIDA_ID", $CABECERA['REGNRO'])->get();

                foreach( $detalleSalida as $detail)
                (new StockController())->actualizar_existencia(  $detail['ITEM'],  $detail['CANTIDAD'], 'INC');

                Salidas_detalles::where("SALIDA_ID", $CABECERA['REGNRO'])->delete();

                foreach ($DETALLE as $row) :
                    $datarow = $row;
                    $datarow['SALIDA_ID'] = $n_salida->REGNRO;
                    $d_salida =  new Salidas_detalles();
                    $d_salida->fill($datarow);
                    $d_salida->save();
                    //Actualizar existencia
                    (new StockController())->actualizar_existencia(  $datarow['ITEM'],  $datarow['CANTIDAD'], 'DEC');
                    


                endforeach;



                //Cambiar estado de ficha de produccion a Despachado (Si se ha indicado un numero de Ficha de prod.)
                if (isset($CABECERA['PRODUCCION_ID'])   &&  $CABECERA['PRODUCCION_ID'] != "") {
                    Ficha_produccion::find($CABECERA['PRODUCCION_ID'])
                        ->fill(["ESTADO" => "DESPACHADO", "RECIBIDO_POR" => session("ID")])->save();
                }

                DB::commit();
                return response()->json(['ok' =>  "Salida actualizada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }





    public function  delete($id)
    {
        try {
            DB::beginTransaction();
            Salidas::find($id)->delete();
            //deshacr
            $NotaResiduoDetalle = Salidas_detalles::where("SALIDA_ID",  $id)->get();
            
            foreach ($NotaResiduoDetalle  as $nrd) {
                (new StockController())->actualizar_existencia($nrd->ITEM, $nrd->CANTIDAD, 'INC');
            }
            Salidas_detalles::where("SALIDA_ID",  $id)->delete();
            DB::commit();
            return response()->json(['ok' =>  "Registro de salida eliminado"]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['err' =>   $ex->getMessage()]);
        }
    }
}

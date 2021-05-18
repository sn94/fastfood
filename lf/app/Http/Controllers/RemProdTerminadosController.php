<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Ficha_produccion;
use App\Models\Remision_de_terminados;
use App\Models\Remision_de_terminados_detalle;
use App\Models\Stock_existencias;
use Exception;
use Illuminate\Support\Facades\DB;

class RemProdTerminadosController extends Controller
{




    public function index()
    {

        $sucursal = session("SUCURSAL");
        $listaRemisiones =  Remision_de_terminados::where("SUCURSAL", $sucursal)->orderBy("created_at", "DESC")->paginate(10);

        if (request()->ajax())
            return view("remision_de_terminados.index.grill", ['REMISION' =>  $listaRemisiones]);
        else
            return view("remision_de_terminados.index.index", ['REMISION' =>  $listaRemisiones]);
    }



    public function create($PRODUCCIONID =  NULL)
    {
        if (request()->getMethod()  ==  "GET") {


            if (is_null($PRODUCCIONID))
                return view('remision_de_terminados.create.index');
            else {
                $produccion = Ficha_produccion::find($PRODUCCIONID);
                $pro_detalle = $produccion->detalle_produccion;

                return view(
                    'remision_de_terminados.create.index',
                    [
                        "PRODUCCION" =>  $produccion,
                        'PRODUCCION_DETALLE' => $pro_detalle
                    ]
                );
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

                    $existencia = Stock_existencias::where("SUCURSAL", session("SUCURSAL"))
                        ->where("STOCK_ID",  $datarow['ITEM'])->first();
                    $existencia->CANTIDAD =  $existencia->CANTIDAD - $datarow['CANTIDAD'];
                    $existencia->save(); //disminuir

                endforeach;

                //Actualizar estado
                //Listo, cumplimentado
                if (isset($CABECERA['PRODUCCION_ID'])) {
                    Ficha_produccion::find($CABECERA['PRODUCCION_ID'])
                        ->fill(["ESTADO" => "LISTO", "FINALIZADO_POR" => session("ID")])->save();
                }
                DB::commit();
                return response()->json(['ok' =>  "Remisión registrada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }
}

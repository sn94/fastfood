<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ficha_produccion;
use App\Models\Ficha_produccion_detalles;
 
use Exception;
use Illuminate\Support\Facades\DB;

class FichaProduccionController extends Controller
{


    public function index()
    {

        $sucursal = session("SUCURSAL");

        $fecha=  request()->has("FECHA") ?  request()->input("FECHA")  :  date('Y-m-d') ; 
        $fecha= $fecha == ""?  date('Y-m-d')   :  $fecha;
        $lista = Ficha_produccion::where("SUCURSAL", $sucursal)
        ->where("FECHA",  $fecha);

        //El formato de los datos
        $formato =  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json") {
            $lista = $lista->get();
            return response()->json($lista);
        }
    }


    public function create($PRODUCCIONID = null)
    {
        if (request()->getMethod()  ==  "GET") {

            if (is_null($PRODUCCIONID))
                return view('ficha_produccion.create');
            else {
                $FORMATO =  request()->header("formato", "html");
                if ($FORMATO  == "html") {
                    $PRODUCCION = Ficha_produccion::find($PRODUCCIONID);
                    return view('ficha_produccion.create',  ['PRODUCCION' => $PRODUCCION, 'PRODUCCION_ID' =>   $PRODUCCIONID]);
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
}

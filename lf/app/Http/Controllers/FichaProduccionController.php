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
        $fecha =  request()->has("FECHA") ?  request()->input("FECHA")  : null;
        $fecha_desde =  request()->has("FECHA_DESDE") ?  request()->input("FECHA_DESDE")  : null;
        $fecha_hasta =  request()->has("FECHA_HASTA") ?  request()->input("FECHA_HASTA")  : null;



        $lista = Ficha_produccion::where("SUCURSAL", $sucursal);
        if (is_null($fecha)) {
            if (!(is_null($fecha_desde))     &&    !(is_null($fecha_hasta)))
                $lista = $lista->where("FECHA", ">=",  $fecha_desde)->where("FECHA", "<=",  $fecha_hasta);
            else
                $lista = $lista->orderBy("created_at", "DESC");
        } else
            $lista = $lista->where("FECHA",  $fecha);

        //El formato de los datos
        $formato =  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json") {
            $lista = $lista->get();
            return response()->json($lista);
        }
        if ($formato == "pdf") {
            $lista = $lista->get();
            return $this->responsePdf(
                "ficha_produccion.index.grill",
                ['FICHAS_PRODUCCION' => $lista],
                "Órdenes de producción"
            );
        }


        if (request()->ajax())
            return view("ficha_produccion.index.grill",   ['FICHAS_PRODUCCION' =>  $lista->paginate(20)]);
        else
            return view("ficha_produccion.index.index",   ['FICHAS_PRODUCCION' =>  $lista->paginate(20)]);
    }


    public function create($PRODUCCIONID = null)
    {
        if (request()->getMethod()  ==  "GET") {

            if (is_null($PRODUCCIONID))
                return view('ficha_produccion.create.index');
            else {

                //Edit  
                $PRODUCCION = Ficha_produccion::find($PRODUCCIONID);
                $DETALLE = $PRODUCCION->detalle_produccion;


                return view(
                    'ficha_produccion.create.index',
                    [
                        'PRODUCCION' => $PRODUCCION, 'DETALLE' => $DETALLE,
                        'PRODUCCION_ID' =>   $PRODUCCIONID
                    ]
                );
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
                $f_produccion =  ($ANTI_SPOOFING_METHOD ==  "POST") ? (new Ficha_produccion()) : (Ficha_produccion::find($Datos['CABECERA']['REGNRO']));
                $f_produccion->fill($CABECERA);
                $f_produccion->save();

                if ($ANTI_SPOOFING_METHOD ==  "PUT") {
                    //Borrar detalles
                    Ficha_produccion_detalles::where("PRODUCCION_ID",  $Datos['CABECERA']['REGNRO'])->delete();
                }
                //DETALLE
                //Borrar detalle anterior
                Ficha_produccion_detalles::where("PRODUCCION_ID", $f_produccion->REGNRO)->delete();

                foreach ($DETALLE_PRODUCTOS as $row) :
                    $datarow = $row;
                    $datarow['PRODUCCION_ID'] = $f_produccion->REGNRO;
                    $d_compra =  new Ficha_produccion_detalles();
                    $d_compra->fill($datarow);
                    $d_compra->save();
                endforeach;


                foreach ($DETALLE_INGREDIEN as $row) :
                    $datarow = $row;
                    $datarow['PRODUCCION_ID'] = $f_produccion->REGNRO;

                    $d_compra =  new Ficha_produccion_detalles();
                    $d_compra->fill($datarow);
                    $d_compra->save();
                endforeach;

                foreach ($DETALLE_INSUMOS as $row) :
                    $datarow = $row;
                    $datarow['PRODUCCION_ID'] = $f_produccion->REGNRO;

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




    public function get($PRODUCCIONID)
    {

        $ficha = Ficha_produccion::find($PRODUCCIONID);
        $detalle =   Ficha_produccion_detalles::where("PRODUCCION_ID",  $PRODUCCIONID)->with("stock")->get();

        $formato =  request()->header("formato");

        //Si es JSON retornar
        if ($formato == "json")
            return response()->json(["HEADER" =>  $ficha, "DETALLE" => $detalle]);
    }


    public function delete($PRODUCCIONID)
    {
        try {
            DB::beginTransaction();
            Ficha_produccion::find($PRODUCCIONID)->delete();
            Ficha_produccion_detalles::where("PRODUCCION_ID",  $PRODUCCIONID)->delete();
            DB::commit();
            return response()->json(['ok' =>  "Eliminado !"]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['err' =>   $ex->getMessage()]);
        }
    }
}

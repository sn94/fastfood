<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ficha_produccion;
use App\Models\Nota_pedido_detalles;
use App\Models\Nota_residuos;
use App\Models\Nota_residuos_detalle;
use App\Models\Stock_existencias;
use Exception;
use Illuminate\Support\Facades\DB;

class NotaResiduosController extends Controller
{




    public function index()
    {

        $sucursal = session("SUCURSAL");
        $fecha_desde= request()->has("FECHA_DESDE") ? request()->input("FECHA_DESDE") : NULL;
        $fecha_hasta= request()->has("FECHA_HASTA") ? request()->input("FECHA_HASTA") : NULL;

        $listaResiduos =  Nota_residuos::where("SUCURSAL", $sucursal)->orderBy("created_at", "DESC");
        if(  !is_null($fecha_desde)   &&  !is_null($fecha_hasta)   )
        $listaResiduos=  $listaResiduos->where("FECHA", ">=", $fecha_desde)->where("FECHA", "<=", $fecha_hasta);

        //El formato de los datos
        $formato =  request()->header("formato");


        //Si es JSON retornar
        if ($formato == "json")  return response()->json($listaResiduos->get());

        if( $formato == "pdf")  return $this->responsePdf( "nota_residuos.index.simple",  [ "NOTAS_RESIDUOS"=> $listaResiduos->get()], "Notas de residuo");

        $listaResiduos =  $listaResiduos->paginate(20);
        if (request()->ajax())
            return view("nota_residuos.index.grill", ['NOTAS_RESIDUOS' =>  $listaResiduos]);
        else
            return view("nota_residuos.index.index", ['NOTAS_RESIDUOS' =>  $listaResiduos]);
    }



    public function create($PRODUCCIONID =  NULL)
    {
        if (request()->getMethod()  ==  "GET") {


            if (is_null($PRODUCCIONID))
                return view('nota_residuos.create.index');
            else {
                $produccion = Ficha_produccion::find($PRODUCCIONID);
                return view('nota_residuos.create.index', ["PRODUCCION" =>  $produccion]);
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

                    $d_nota_residuos =  new Nota_residuos_detalle();
                    $d_nota_residuos->fill($datarow);
                    $d_nota_residuos->save();
                    //Actualizar existencia
                  //  (new StockController())->actualizar_existencia($datarow['ITEM'], $datarow['CANTIDAD'], 'INC');



                endforeach;


                DB::commit();
                return response()->json(['ok' =>  "Nota de residuos registrada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function update($ID_  = null)
    {

        if (request()->isMethod("GET")) {
            $NotaResiduo = Nota_residuos::find($ID_);
            $NotaResiduoDetalle = $NotaResiduo->residuos_detalle;
            return view(
                'nota_residuos.create.index',
                ["NOTA_RESIDUO" => $NotaResiduo, "NOTA_RESIDUO_DETALLE" => $NotaResiduoDetalle]
            );
        }


        $Datos =  request()->input();
        $CABECERA = $Datos['CABECERA'];
        $DETALLE = $Datos['DETALLE'];

        $N_R_id =  $CABECERA['REGNRO'];
        try {
            DB::beginTransaction();
            $NotaResiduo =  Nota_residuos::find($N_R_id);
            $NotaResiduo->fill($CABECERA);
            $NotaResiduo->save();
            //Volver al estado inicial
       /*     $NotaResiduoDetalle = Nota_residuos_detalle::where("NRESIDUO_ID",  $N_R_id)->get();
            foreach ($NotaResiduoDetalle  as $nrd) {
                (new StockController())->actualizar_existencia($nrd->ITEM, $nrd->CANTIDAD, 'DEC');
            }*/
            //borrar
            Nota_residuos_detalle::where("NRESIDUO_ID",  $N_R_id)->delete();

            foreach ($DETALLE as $row) :
                $datarow = $row;
                $datarow['NRESIDUO_ID'] = $N_R_id;

                $d_nota_residuos =  new Nota_residuos_detalle();
                $d_nota_residuos->fill($datarow);
                $d_nota_residuos->save();
                //Actualizar existencia
               // (new StockController())->actualizar_existencia($datarow['ITEM'], $datarow['CANTIDAD'], 'INC');

            endforeach;
            DB::commit();
            return response()->json(['ok' =>  "Nota de residuos actualizada"]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['err' =>   $ex->getMessage()]);
        }
    }



    public function  delete($id)
    {
        try {
            DB::beginTransaction();
            Nota_residuos::find($id)->delete();
            //deshacr
           /* $NotaResiduoDetalle = Nota_residuos_detalle::where("NRESIDUO_ID",  $id)->get();
           foreach ($NotaResiduoDetalle  as $nrd) {
                (new StockController())->actualizar_existencia($nrd->ITEM, $nrd->CANTIDAD, 'DEC');
            }*/
            Nota_residuos_detalle::where("NRESIDUO_ID",  $id)->delete();
            DB::commit();
            return response()->json(['ok' =>  "Nota de residuos eliminada"]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['err' =>   $ex->getMessage()]);
        }
    }
}

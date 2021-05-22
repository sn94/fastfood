<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Ficha_produccion;
use App\Models\Remision_de_terminados;
use App\Models\Remision_de_terminados_detalle;
use App\Models\Stock_existencias;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

class RemProdTerminadosController extends Controller
{




    public function index($modoVisualizacion = "cocina")
    {

        $sucursal = session("SUCURSAL");
        $fecha_desde = request()->has("FECHA_DESDE") ? request()->input("FECHA_DESDE") : NULL;
        $fecha_hasta = request()->has("FECHA_HASTA") ? request()->input("FECHA_HASTA") : NULL;
        $estado = request()->has("ESTADO") ? request()->input("ESTADO") : "P";

        $listaRemisiones =  Remision_de_terminados::where("SUCURSAL", $sucursal);

        if (!is_null($fecha_desde)   &&  !is_null($fecha_hasta))
            $listaRemisiones =  $listaRemisiones->where("FECHA", ">=", $fecha_desde)->where("FECHA", "<=", $fecha_hasta);

        $listaRemisiones = $listaRemisiones->where("ESTADO", $estado)->orderBy("created_at", "DESC");

        //El formato de los datos
        $formato =  request()->header("formato");


        //Si es JSON retornar
        if ($formato == "json")  return response()->json($listaRemisiones->get());

        if ($formato == "pdf")  return $this->responsePdf(
            "remision_de_terminados.index.simple",
            ["REMISION" => $listaRemisiones->get()],
            "Notas de Remisión"
        );

        $listaRemisiones = $listaRemisiones->paginate(20);

        if (request()->ajax()) {
            if ($modoVisualizacion == "cocina")
                return view("remision_de_terminados.index.grill", ['REMISION' =>  $listaRemisiones]);
            if ($modoVisualizacion == "confirmar")
                return view("remision_de_terminados.confirmar.grill", ['REMISION' =>  $listaRemisiones]);
        } else {
            if ($modoVisualizacion == "cocina")
                return view("remision_de_terminados.index.index", ['REMISION' =>  $listaRemisiones]);
            if ($modoVisualizacion == "confirmar")
                return view("remision_de_terminados.confirmar.index", ['REMISION' =>  $listaRemisiones]);
        }
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
                $_n_remision = new Remision_de_terminados();
                $_n_remision->fill($CABECERA);
                $_n_remision->save();
                //DETALLE

                foreach ($DETALLE as $row) :
                    $datarow = $row;
                    $datarow['REMISION_ID'] = $_n_remision->REGNRO;

                    $d_compra =  new Remision_de_terminados_detalle();
                    $d_compra->fill($datarow);
                    $d_compra->save();
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




    public function view($id =  NULL)
    {

        $Remision = Remision_de_terminados::find($id);
        $Detalle =  $Remision->remision_detalle;
        return view('remision_de_terminados.view.index', ['REMISION' => $Remision, 'DETALLE' =>  $Detalle]);
    }


    public function update($id =  NULL)
    {
        if (request()->getMethod()  ==  "GET") {

            $Remision = Remision_de_terminados::find($id);
            $Detalle =  $Remision->remision_detalle;
            return view('remision_de_terminados.create.index', ['REMISION' => $Remision, 'DETALLE' =>  $Detalle]);
        } else {

            $Datos = request()->input();
            $CABECERA = $Datos['CABECERA'];
            $DETALLE = $Datos['DETALLE'];

            try {


                DB::beginTransaction();
                //CABECERA
                $_n_remision = Remision_de_terminados::find($CABECERA['REGNRO']);
                if ($_n_remision->ESTADO != "P")
                    return response()->json(['err' =>  "Nota de remisión ya fue aceptada. No puede editarse."]);


                $_n_remision->fill($CABECERA);
                $_n_remision->save();
                //DETALLE
                Remision_de_terminados_detalle::where("REMISION_ID", $CABECERA['REGNRO'])->delete();
                foreach ($DETALLE as $row) :
                    $datarow = $row;
                    $datarow['REMISION_ID'] = $_n_remision->REGNRO;
                    $d_compra =  new Remision_de_terminados_detalle();
                    $d_compra->fill($datarow);
                    $d_compra->save();

                //  (new StockController())->actualizar_existencia($datarow['ITEM'], $datarow['CANTIDAD'], 'INC');
                endforeach;

                DB::commit();
                return response()->json(['ok' =>  "Remisión actualizada"]);
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
            $nr =  Remision_de_terminados::find($id);

            if ($nr->ESTADO == "P")  $nr->delete();
            else  return response()->json(['err' =>  "Nota de remisión ya fue aceptada. No puede borrarse."]);

            Remision_de_terminados_detalle::where("REMISION_ID",  $id)->delete();
            DB::commit();
            return response()->json(['ok' =>  "Nota de remisión eliminada"]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['err' =>   $ex->getMessage()]);
        }
    }

    public function  confirmar($id)
    {
        try {
            DB::beginTransaction();
            $nr =  Remision_de_terminados::find($id);


            if ($nr->ESTADO == "C")
                return response()->json(['err' =>  "Nota de remisión ya fue aceptada anteriormente."]);

            $nr->ESTADO = "C";
            $nr->save();
            //Actualizar stock
            $detalleRemision =   $nr->remision_detalle;
            foreach ($detalleRemision as $detalle) (new StockController())->actualizar_existencia($detalle->ITEM, $detalle->CANTIDAD, 'INC');

            DB::commit();
            return response()->json(['ok' =>  "Nota de remisión Confirmada. Stock actualizado."]);
        } catch (Exception $ex) {

            DB::rollBack();
            return response()->json(['err' =>   $ex->getMessage()]);
        }
    }
}

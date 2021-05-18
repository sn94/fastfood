<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Models\Ficha_produccion; 

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
        $listaResiduos =  Nota_residuos::where("SUCURSAL", $sucursal)->orderBy("created_at", "DESC")->paginate(10);

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

                    $d_compra =  new Nota_residuos_detalle();
                    $d_compra->fill($datarow);
                    $d_compra->save();
                    //Actualizar existencia
                    $existencia = Stock_existencias::where("SUCURSAL", session("SUCURSAL"))
                        ->where("STOCK_ID",  $datarow['ITEM'])->first();
                    $existencia->CANTIDAD =  $existencia->CANTIDAD +  $datarow['CANTIDAD'];
                    $existencia->save(); //disminuir

                endforeach;


                DB::commit();
                return response()->json(['ok' =>  "Nota de residuos registrada"]);
            } catch (Exception  $ex) {
                DB::rollBack();
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }


 
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libs\pdf_gen\PDF;
use App\Models\Ciudades;
use App\Models\Proveedores;
use Exception;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class ProveedoresController extends Controller
{

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function index()
    {

        $buscado = ""; //busqueda por patrones
        $CIUDAD = "";

        if (request()->method() ==  "POST") {
            $buscado =  request()->has("buscado") ?  request()->input("buscado") :  "";
            $CIUDAD =  request()->has("CIUDAD") ?  request()->input("CIUDAD") :  "";
        }
        
        $proveedores = Proveedores::orderBy("created_at");
        if ($buscado !=  "") {
            $proveedores =  $proveedores
                ->whereRaw(" CEDULA_RUC LIKE '%$buscado%' or  NOMBRE LIKE '%$buscado%'  ");
        }


        if ($CIUDAD !=  "") { 
            $proveedores =  $proveedores
                ->where("CIUDAD", $CIUDAD);
        }
        
        $formato =  request()->header("formato");

        $formato =  is_null($formato) ?  "html"  :   $formato;

        //Si es JSON retornar
        if ($formato == "json") {
            $proveedores =  $proveedores->get();
            return response()->json($proveedores);
        }

        if ($formato ==   "pdf")
            return $this->responsePdf(  "proveedores.grill.simple",  $proveedores->get(), "Proveedores");

        if ($formato ==  "html") { ///Html
         //   dd($proveedores->get());
            $resultados =  $proveedores->paginate(20);
            if (request()->ajax())
                return view('proveedores.grill.index',  ['proveedores' =>  $resultados]);
            else
                return view('proveedores.index');
        }
    }




    public function create()
    {
        if (request()->getMethod()  ==  "GET")
            if (request()->ajax())
                return view("proveedores.create.ajax");
            else
                return view('proveedores.create.index');
        else {

            if ($this->ruc_cedula_registrado(request()->input("CEDULA_RUC")))
                return response()->json(['err' =>  "CÉDULA/RUC ya existe"]);

            try {
                $nuevo_proveedor =  new Proveedores();
                $nuevo_proveedor->fill(request()->input());
                $nuevo_proveedor->save();
                return response()->json(['ok' =>  $nuevo_proveedor->REGNRO]);
            } catch (Exception  $ex) {
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function update($id = NULL)
    {
        if (request()->getMethod()  ==  "GET") {
            $cli =  Proveedores::find($id);
            return view('proveedores.create.index',  ['proveedores' =>  $cli]);
        } else {


            try {
                $id_ = request()->input("REGNRO");
                $nuevo_proveedor =  Proveedores::find($id_);


                if ($nuevo_proveedor->CEDULA_RUC !=  request()->input("CEDULA_RUC")  && 
                 $this->ruc_cedula_registrado(request()->input("CEDULA_RUC")))
                    return response()->json(['err' =>  "CÉDULA/RUC ya existe"]);


                $nuevo_proveedor->fill(request()->input());
                $nuevo_proveedor->save();
                return response()->json(['ok' =>  $nuevo_proveedor->REGNRO]);
            } catch (Exception  $ex) {
                return response()->json(['err' =>   $ex->getMessage()]);
            }
        }
    }



    public function delete($id = NULL)
    {
        $reg = Proveedores::find($id);
        if (!is_null($reg)) {
            $reg->delete();
            return response()->json(['ok' =>  "Proveedor eliminado"]);
        } else {
            return response()->json(['err' =>  "ID inexistente"]);
        }
    }




    //VALIDACIONES


    private function  ruc_cedula_registrado($arg)
    {

        $cli =  Proveedores::where("CEDULA_RUC",  $arg)->first();
        return  !is_null($cli);
    }




    
    public function pdf($lista)
    {


        $html = <<<EOF
		<style>
		table.tabla{
			color: #000222;
			font-family: Arial;
			font-size: 8pt;
			border-left: none; 
		}
		
		tr.header th{ 
			font-weight: bold;
			border-bottom: 1px solid black;
		} 
		tr.footer td{  
			font-weight: bold; 
			border-top: 1px solid black;
		} 
		 
		</style>

		<table class="tabla">
		<thead >
		<tr class="header">
		<th style="text-align:left;">CÉDULA/RUC</th>
		<th style="text-align:left;">NOMBRES</th>
		<th style="text-align:right;">TELÉF.</th>
		<th style="text-align:right;">CELULAR</th>
		<th style="text-align:right;">CIUDAD</th>
		</tr>
		</thead>
		<tbody>
		EOF;


        foreach ($lista as $row) {

            $CEDULARUC = $row->CEDULA_RUC;
            $NOMBRES = $row->NOMBRE;
            $TELEF =  $row->TELEFONO;
            $CELULAR = $row->CELULAR;
            $CIUDAD =  is_null($row->ciudad_) ? "-" : $row->ciudad_->ciudad;

            $html .= "<tr><td style=\"text-align:left;\">$CEDULARUC</td> <td style=\"text-align:left;\">$NOMBRES</td> <td style=\"text-align:right;\" >$TELEF</td> <td style=\"text-align:right;\">$CELULAR</td><td style=\"text-align:right;\">$CIUDAD</td> </tr>";
        }

        $html .= "</tbody> </table> ";
        /********* */

        
        $pdf = new  PDF();
        $TITULO_DOCUMENTO =  "Proveedores";
        $pdf->prepararPdf("$TITULO_DOCUMENTO.pdf",  $TITULO_DOCUMENTO, "");
        $pdf->generarHtml($html);
        return $pdf->generar();
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use PDFGEN;



  class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    

  


    /**
     * @param ViewName
     * @param Data
     * @param Doc.Title
     */
    public function responsePdf( $viewName,  $data,  $title)
    {

        //Nombre de vista
        //data
        //doc title
        
       $pdf = PDFGEN::loadView("$viewName", ["datalist" =>  $data, "titulo" => "$title"]);
       return $pdf->download("$title.pdf"); 
 
    }
}

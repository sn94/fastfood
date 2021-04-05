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
        
        $viewParam=  [];
        if( is_array( $data))
        $viewParam=array_merge(  [   "titulo" => "$title"],  $data);
        else  $viewParam=   [   "titulo" => "$title",  "datalist"=>  $data];

       $pdf = PDFGEN::loadView("$viewName",   $viewParam     );
       return $pdf->download("$title.pdf"); 
 
    }
}

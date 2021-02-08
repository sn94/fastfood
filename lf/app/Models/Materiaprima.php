<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiaprima extends Model
{
    use HasFactory;
   
    protected  $table=  "materia_prima";
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [
        'CODIGO','DESCRIPCION','PCOSTO','STOCK_MAX','STOCK_MIN',
         'IMG',  'TRIBUTO', 'MEDIDA', 'TRIBUTO'
        
    ];


    
}

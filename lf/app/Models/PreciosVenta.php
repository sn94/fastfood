<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreciosVenta extends Model
{
    use HasFactory;
     
    protected $table= "stock_precios";
    protected $primaryKey = 'REGNRO';
    public $timestamps = false;
    protected $fillable = [ 'STOCK_ID','DESCRIPCION','ENTERO','MITAD', 'PORCION' ];

 
     
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock_existencias extends Model
{
    use HasFactory;
   
    protected $table= "stock_existencias";
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [
        'STOCK_ID','SUCURSAL','CANTIDAD'
    ];


    


}

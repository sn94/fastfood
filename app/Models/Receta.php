<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    use HasFactory;
     
    protected $table= "receta";
    protected $primaryKey = 'REGNRO';
    public $timestamps = false;
    protected $fillable = [ 'STOCK_ID','CANTIDAD','MEDIDA_','MPRIMA_ID' ];

 


    public function materia_prima()
    {
        return $this->belongsTo( Stock::class, 'MPRIMA_ID' ,  'REGNRO' ); 
    }
     
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  Ficha_produccion_detalles extends Model
{
    use HasFactory;
     
    protected $table= "ficha_produccion_detalles";
    protected $primaryKey = 'REGNRO';
    public $timestamps = false;
    protected $fillable = [  'PRODUCCION_ID', 'ITEM', 'CANTIDAD', 'TIPO', 'MEDIDA' ];

 

    public function stock()
    {
        return $this->hasOne( Stock::class, 'REGNRO' , "ITEM"); // 
    }
   

    public function produccion()
    {
        return $this->belongsTo(Ficha_produccion::class, 'REGNRO', 'PRODUCCION_ID' );
    }



}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas_det extends Model
{
    use HasFactory;
    

    protected  $table= "ventas_det";
    protected $primaryKey = 'REGNRO';
    
    public $timestamps = false;
    protected $fillable = [
        'VENTA_ID','ITEM','CANTIDAD','P_UNITARIO','EXENTA','TOT5','TOT10'
        
    ];


    public function producto()
    {
        return $this->hasOne(Stock::class, 'REGNRO', 'ITEM'  ); 
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota_residuos_detalle extends Model
{
    use HasFactory;
     
    protected $table= "nota_residuos_detalle";
    protected $primaryKey = 'REGNRO';
    public $timestamps = false;
    protected $fillable = [  'NRESIDUO_ID', 'ITEM', 'CANTIDAD', 'TIPO' , 'MEDIDA'];

 

    
    public function stock()
    {
        return $this->hasOne(Stock::class, 'REGNRO', 'ITEM'  ); 
    }


    public function recepcion()
    {
        return $this->belongsTo( Nota_residuos::class, 'REGNRO', 'NRESIDUO_ID' );
    }
}

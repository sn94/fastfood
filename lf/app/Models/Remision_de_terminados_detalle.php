<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remision_de_terminados_detalle extends Model
{
    use HasFactory;
     
    protected $table= "remi_produ_terminados_detalle";
    protected $primaryKey = 'REGNRO';
    public $timestamps = false;
    protected $fillable = [  'REMISION_ID', 'ITEM', 'CANTIDAD', 'TIPO' , 'MEDIDA'];

 

    public function stock(){
        return $this->hasOne( Stock::class,  'REGNRO', 'ITEM');
    }
   

    public function recepcion()
    {
        return $this->belongsTo( Nota_residuos::class, 'REGNRO', 'REMISION_ID' );
    }
}

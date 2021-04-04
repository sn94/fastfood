<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota_pedido_detalles extends Model
{
    use HasFactory;
     
    protected $table= "nota_pedido_detalles";
    protected $primaryKey = 'REGNRO';
    public $timestamps = false;
    protected $fillable = [  'NPEDIDO_ID', 'ITEM', 'CANTIDAD', 'TIPO' ];

 

    
    public function  stock(){
        return $this->hasOne(Stock::class, 'REGNRO',  'ITEM'  )->withDefault();;
    }
   

    public function pedido()
    {
        return $this->belongsTo(Nota_pedido::class,   'NPEDIDO_ID',  'REGNRO' );
    }
}

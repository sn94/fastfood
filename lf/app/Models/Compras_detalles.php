<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compras_detalles extends Model
{
    use HasFactory;
     
    protected $primaryKey = 'REGNRO';
    public $timestamps = false;
    protected $fillable = [  'COMPRA_ID', 'ITEM', 'CANTIDAD', 'P_UNITARIO', 'EXENTA', 'IVA5',  'IVA10' ];

 

    
   

    public function compra()
    {
        return $this->belongsTo(Compras::class, 'REGNRO', 'COMPRA_ID' );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    use HasFactory;
   
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [
        'CODIGO','BARCODE','BOTON','FAMILIA','PVENTA','DESCRIPCION','PCOSTO','STOCK_MAX','STOCK_MIN',
        'ULT_COMPRA', 'IMG',  'TRIBUTO',  'TIPO',  'MEDIDA'
        
    ];


  


    public function compras_detalle()
    {
        return $this->belongsToMany(Compras_detalle::class, 'ITEM', 'REGNRO'  ); 
    }


}

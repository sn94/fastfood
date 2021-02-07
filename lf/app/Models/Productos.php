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
        'CODIGO','BARCODE','BOTON','FAMILIA','PVENTA','DESCRIPCION','PCOSTO','STOCKTOTAL','STOCK_MIN',
        'PROVEEDOR','ULT_COMPRA', 'IMG',  'TRIBUTO',  'TIPO',  'MEDIDA'
        
    ];


    public function proveedor()
    {
        return $this->belongsTo(Proveedores::class,  "PROVEEDOR", "REGNRO"  );
    }




    public function compras_detalle()
    {
        return $this->belongsToMany(Compras_detalle::class, 'ITEM', 'REGNRO'  ); 
    }


}

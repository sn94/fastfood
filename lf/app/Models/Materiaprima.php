<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiaprima extends Model
{
    use HasFactory;
   
    protected  $table=  "materia_prima";
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [
        'CODIGO','DESCRIPCION','PCOSTO','STOCKTOTAL','STOCK_MIN',
        'PROVEEDOR', 'IMG',  'TRIBUTO', 'MEDIDA', 'TRIBUTO'
        
    ];


    public function proveedor()
    {
        //                                              foreign     ownerkey
        return $this->belongsTo(Proveedores::class,  "PROVEEDOR", "REGNRO");
    }
}

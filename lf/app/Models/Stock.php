<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
   
    protected $table= "stock";
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [
        'CODIGO','BARCODE','BOTON','FAMILIA',
        'PVENTA' , 'PVENTA_MITAD' , 'PVENTA_EXTRA' ,'DESCRIPCION'  ,  'DESCR_CORTA','PCOSTO',
        'STOCK_MAX','STOCK_MIN', 'IMG', 'IMG_EXT_URL',  'TRIBUTO',  'TIPO',  'MEDIDA',  'PRESENTACION',
        'PRECIOS_VARIOS'
    ];


    public function precios(){
        return $this->hasMany( PreciosVenta::class, "STOCK_ID", "REGNRO");
    }



    public function receta(){
        return $this->hasMany( Receta::class, "STOCK_ID", "REGNRO");
    }
    public function combos(){
        return $this->hasMany( Combos::class, "COMBO_ID", "REGNRO");
    }

    public function compras_detalle()
    {
        return $this->belongsToMany(Compras_detalle::class, 'ITEM', 'REGNRO'  ); 
    }




    public function unidad_medida(){
        return $this->hasOne( Medidas::class, "REGNRO", "MEDIDA")->withDefault();
    }



}

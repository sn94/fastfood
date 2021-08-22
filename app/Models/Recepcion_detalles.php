<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recepcion_detalles extends Model
{
    use HasFactory;
     
    protected $table= "recepcion_detalles";
    protected $primaryKey = 'REGNRO';
    public $timestamps = false;
    protected $fillable = [  'RECEPCION_ID', 'ITEM', 'CANTIDAD', 'ESPRODUCTO' ];

 

    
   

    public function recepcion()
    {
        return $this->belongsTo(Recepcion::class, 'REGNRO', 'RECEPCION_ID' );
    }
}

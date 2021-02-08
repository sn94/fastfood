<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salidas_detalles extends Model
{
    use HasFactory;
     
    protected $primaryKey = 'REGNRO';
    public $timestamps = false;
    protected $fillable = [  'SALIDA_ID', 'ITEM', 'CANTIDAD' ];

 

    
   

    public function salida()
    {
        return $this->belongsTo( Salidas::class, 'REGNRO', 'SALIDA_ID' );
    }
}

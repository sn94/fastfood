<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compras extends Model
{
    use HasFactory;
     
    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [  'NUMERO', 'CONDICION', 'FECHA', 'PROVEEDOR' ,  'CONCEPTO'];

 

    public function compras_detalle()
    {
        return $this->hasMany( Compras_detalle::class, 'COMPRA_ID',  'REGNRO');
    }


}

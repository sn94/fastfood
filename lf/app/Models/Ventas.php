<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    use HasFactory;
    

    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [
        'NUMERO','FECHA','CLIENTE'  ,  'ESTADO', 'FORMA'
        
    ];




    public function productos()
    {
        return $this->hasMany(  Ventas_det::class,   'VENTA_ID', 'REGNRO');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    use HasFactory;
    

    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [
        'CEDULA_RUC','NOMBRE','DIRECCION','CIUDAD','TELEFONO','CELULAR','EMAIL'
        
    ];


    
    public function ventas()
    {
        return $this->belongsToMany(Ventas::class, 'CLIENTE', 'REGNRO'  ); 
    }


    public function ciudad_(){
        return $this->belongsTo( Ciudades::class,      'CIUDAD', 'regnro' );
    }
}

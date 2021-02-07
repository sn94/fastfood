<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    use HasFactory;
    

    protected $primaryKey = 'REGNRO';
    public $timestamps = true;
    protected $fillable = [
        'CEDULA_RUC','NOMBRE','DIRECCION','CIUDAD','TELEFONO','CELULAR','EMAIL',  'WEB'
        
    ];


    public function productos()
    {
        return $this->hasMany(Productos::class,   'PROVEEDOR', 'REGNRO');
    }

    public function materiaprimas()
    {
        return $this->hasMany(Materiaprima::class,   'PROVEEDOR', 'REGNRO');
    }
}

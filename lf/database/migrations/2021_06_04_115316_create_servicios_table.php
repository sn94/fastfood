<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       if(  ! Schema::hasTable("servicios"))
        Schema::create('servicios', function (Blueprint $table) {
            $table->increments("REGNRO");
            $table->string("DESCRIPCION", 50);
            $table->integer("COSTO")->unsigned();
            $table->integer("ORDEN")->unsigned();
            $table->timestamps();
        });
        if(  ! Schema::hasTable("ventas_det_servicio"))
        Schema::create('ventas_det_servicio', function (Blueprint $table) {
            $table->increments("REGNRO");
            $table->integer("VENTA_ID")->unsigned();
            $table->integer("SERVICIO_ID")->unsigned();
            $table->integer("COSTO")->unsigned();  
        });
    }

    /**
     * Reverse the migrations.
     * php artisan migrate:refresh
     *
     * @return void
     */
    public function down()
    {
      
        Schema::dropIfExists('servicios');
        Schema::dropIfExists('ventas_det_servicio');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string("USUARIO");
            $table->string("PASS");
            $table->string("NIVEL");
            $table->string("ESTADO");
            $table->string("NOMBRES");
            $table->string("CEDULA");
            $table->integer("SUCURSAL")->unsigned();
            $table->integer("CARGO")->unsigned();
            $table->integer("TURNO")->unsigned();
            $table->string("CELULAR");
            $table->string("EMAIL");
            $table->integer("ORDEN")->default(-1);
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}

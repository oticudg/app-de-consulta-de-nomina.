<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNominaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cedula_a')->unsigned();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('des_age');
            $table->string('fec_ing');
            $table->string('den_cargo');
            $table->string('den_ubi');
            $table->timestamps();
        });
        Schema::create('eventos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event');
            $table->timestamps();
        });
        Schema::create('entregados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cedula_a')->unsigned();
            $table->integer('event_id')->unsigned();
            $table->timestamps();

            $table->foreign('cedula_a')->references('id')->on('nominas')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('eventos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nominas');
        Schema::dropIfExists('entregados');
    }
}

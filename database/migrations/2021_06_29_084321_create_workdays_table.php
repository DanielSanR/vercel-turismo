<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkdaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workdays', function (Blueprint $table) {
            $table->id();

            $table->string('day');
            $table->time('opening');
            $table->time('closing');
            $table->integer('time_interval')->default(60);
    
            $table->unsignedBigInteger('entrepreneurship_id')->unsigned()->nullable();
            $table->foreign('entrepreneurship_id')->references('id')->on('entrepreneurships')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workdays');
    }
}

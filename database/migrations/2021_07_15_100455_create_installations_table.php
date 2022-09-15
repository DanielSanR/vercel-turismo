<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installations', function (Blueprint $table) {
            $table->id();

            $table->string('category');
            $table->string('name');
            $table->string('description')->default('');
            $table->integer('capacity');
            $table->float('price');
            $table->integer('quantity');
            $table->string('image_path', 300)->default('assets/images/installation-default.png');

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
        Schema::dropIfExists('installations');
    }
}

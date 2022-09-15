<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntrepreneurshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrepreneurships', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('address');
            $table->string('phone');
            $table->string('locality');
            $table->string('department');
            $table->text('coordinates');
            $table->string('accommodation')->default('no');     

            $table->softDeletes();
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
        Schema::dropIfExists('entrepreneurships');
    }
}

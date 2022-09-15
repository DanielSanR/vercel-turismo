<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalserviceInstallationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localservice_installation', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('installation_id')->unsigned()->nullable();
            $table->foreign('installation_id')->references('id')->on('installations')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('local_service_id')->unsigned()->nullable();
            $table->foreign('local_service_id')->references('id')->on('local_services')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('localservice_installation');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MxcelClientFacilties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mxcel_client_facilities', function (Blueprint $table) {
            $table->increments('facility_id');
            $table->string('name');
            $table->integer('clinic_id');
            $table->integer('client_id');
            $table->string('provider_id')->nullable();
            $table->integer('added_by')->default(0);
            $table->dateTime('added_on')->nullable();
            $table->dateTime('updated_on')->nullable();
            $table->tinyInteger('deleted')->default(0);
            $table->index('clinic_id');
            $table->index('client_id');
            $table->index('deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mxcel_client_facilities');
    }
}

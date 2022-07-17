<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MxcelClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table
        Schema::create('mxcel_client', function (Blueprint $table) {
            $table->increments('client_id');
            $table->string('client_name');
            $table->string('client_key')->unique();
            $table->string('client_secret');
            $table->string('client_url');
            $table->dateTime('added_on')->nullable();
            $table->tinyInteger('deleted');
            $table->index(['client_id', 'deleted']);
            $table->index(['client_key', 'deleted']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // delete table
        Schema::dropIfExists('mxcel_client');
    }
}

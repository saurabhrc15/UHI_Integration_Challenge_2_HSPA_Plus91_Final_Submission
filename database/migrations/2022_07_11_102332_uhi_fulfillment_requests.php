<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UhiFulfillmentRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uhi_fulfillment_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_id');
            $table->string('transaction_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('clinic_id')->nullable();
            $table->text('status_request')->nullable();
            $table->text('on_status_request')->nullable();
            $table->dateTime('added_on')->nullable();
            $table->index('message_id');
            $table->index('transaction_id');
            $table->index('client_id');
            $table->index('clinic_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uhi_fulfillment_requests');
    }
}
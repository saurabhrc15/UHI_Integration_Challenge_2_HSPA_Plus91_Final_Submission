<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UhiSearchRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uhi_search_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_id');
            $table->string('transaction_id')->nullable();
            $table->text('search_request')->nullable();
            $table->string('callback_status')->default('pending');
            $table->dateTime('added_on')->nullable();
            $table->index('message_id');
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uhi_search_requests');
    }
}
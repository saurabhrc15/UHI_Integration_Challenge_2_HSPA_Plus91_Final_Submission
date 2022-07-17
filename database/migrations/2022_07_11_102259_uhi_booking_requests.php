<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UhiBookingRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uhi_booking_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_id');
            $table->string('transaction_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('clinic_id')->nullable();
            $table->text('select_request')->nullable();
            $table->text('on_select_request')->nullable();
            $table->text('init_request')->nullable();
            $table->text('on_init_request')->nullable();
            $table->text('confirm_request')->nullable();
            $table->text('on_confirm_request')->nullable();
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
        Schema::dropIfExists('uhi_booking_requests');
    }
}

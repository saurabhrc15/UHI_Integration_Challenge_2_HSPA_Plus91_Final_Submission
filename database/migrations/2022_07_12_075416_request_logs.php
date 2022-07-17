<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RequestLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_logs', function (Blueprint $table) {
            $table->increments('log_id');
            $table->integer('client_id')->nullable();
            $table->integer('clinic_id')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable();
            $table->string('direction')->nullable();
            $table->string('request_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->longText('request')->nullable();
            $table->longText('response')->nullable();
            $table->integer('response_code')->nullable();
            $table->dateTime('added_on')->nullable();
            $table->index('response_code');
            $table->index('direction');
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
        Schema::dropIfExists('request_logs');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBookingdata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pharmacy_id')->unsigned();
            $table->string('role');
            $table->double('booking_fee');
            $table->double('rate');
            $table->timestamp('start')->nullable();
            $table->timestamp('finish')->nullable();
            $table->integer('public');
            $table->timestamps();

            $table->foreign('pharmacy_id')->references('pharmacy_id')->on('pharmacy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_data');
    }
}

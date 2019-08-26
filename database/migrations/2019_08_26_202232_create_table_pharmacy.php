<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePharmacy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacy', function (Blueprint $table) {
            $table->bigIncrements('pharmacy_id');
            $table->string('branch_identifier', 100);
            $table->double('pharmacist_booking_fee');
            $table->double('pharmacist_rate');
            $table->double('pharmacist_saturday_rate');
            $table->double('pharmacist_sunday_rate');
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
        Schema::dropIfExists('pharmacy');
    }
}

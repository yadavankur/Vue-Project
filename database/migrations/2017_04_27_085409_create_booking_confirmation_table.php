<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingConfirmationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_confirmation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quote_id');
            $table->integer('location_id');
            $table->string('order_id', 30);
            $table->timestamp('requested_date')->nullable();
            $table->timestamp('agreed_date')->nullable();
            $table->timestamp('expedite_date')->nullable();
            $table->boolean('can_expedite')->default(false);
            $table->timestamp('sys_generated_date')->nullable();
            $table->timestamp('confirmed_date')->nullable();
            $table->boolean('booked')->default(false);
            $table->boolean('confirmed')->default(false);
            $table->boolean('fully_paid')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_confirmation');
    }
}

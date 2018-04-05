<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessCalendarHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_calendar_holidays', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('state_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('year')->nullable();
            $table->string('type')->nullable();
            $table->string('value')->nullable(); // date or integer
            $table->string('description')->nullable();
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
        Schema::dropIfExists('business_calendar_holidays');
    }
}

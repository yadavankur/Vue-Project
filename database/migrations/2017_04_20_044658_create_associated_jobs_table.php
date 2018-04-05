<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssociatedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('associated_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quote_id');
            $table->integer('order_quote_id');
            $table->integer('location_id');
            $table->string('order_id', 30);
            $table->boolean('booked')->default(false);
            $table->timestamp('original_delivery_date')->nullable();
            $table->timestamp('new_delivery_date')->nullable();
            $table->timestamp('confirmed_date')->nullable();
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
        Schema::dropIfExists('associated_jobs');
    }
}

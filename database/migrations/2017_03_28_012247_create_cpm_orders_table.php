<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpmOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpm_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quote_id');
            $table->integer('location_id');
            $table->string('order_id', 30);
            $table->integer('service_id');
            $table->integer('activity_id');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->decimal('duration');
            $table->integer('status_id');
            $table->integer('predecessor_id')->nullable();
            $table->integer('successor_id')->nullable();
            $table->integer('version_id')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('cpm_orders');
    }
}

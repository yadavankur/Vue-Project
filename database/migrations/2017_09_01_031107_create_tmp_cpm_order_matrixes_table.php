<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTmpCpmOrderMatrixesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmp_cpm_order_matrixes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quote_id');
            $table->integer('location_id');
            $table->string('order_id', 30);
            $table->integer('service_id');
            $table->integer('activity_id');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->decimal('duration');
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
        Schema::dropIfExists('tmp_cpm_order_matrixes');
    }
}

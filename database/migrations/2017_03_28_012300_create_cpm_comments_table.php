<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpmCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpm_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quote_id');
            $table->integer('location_id');
            $table->string('order_id', 30);
            $table->integer('activity_id');
            $table->integer('cpm_order_id')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('cpm_comments');
    }
}

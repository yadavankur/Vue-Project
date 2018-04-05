<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpmMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpm_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_id');
            $table->integer('activity_id');
            $table->integer('predecessor_id')->nullable();
            $table->integer('successor_id')->nullable();
            $table->string('comment')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->unique(['activity_id', 'predecessor_id', 'successor_id'], 'cpm_master_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cpm_masters');
    }
}

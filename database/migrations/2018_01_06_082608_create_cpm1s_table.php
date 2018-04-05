<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpm1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpm1s', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_id');
            $table->integer('service_group_id')->nullable();
            $table->string('name');
            $table->longText('sql_statement')->nullable();
            $table->decimal('duration');
            $table->string('comment')->nullable();
            $table->boolean('is_full')->default(true);
            $table->longText('action_sql')->nullable(); // sql statement
            $table->boolean('tick_option')->default(true);
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
        Schema::dropIfExists('cpm1s');
    }
}

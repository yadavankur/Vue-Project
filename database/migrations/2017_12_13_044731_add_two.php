<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('testb1s', function (Blueprint $table) {
            //
            $table->boolean('active')->default(true);    
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
        Schema::table('testb1s', function (Blueprint $table) {
            //
        });
    }
}

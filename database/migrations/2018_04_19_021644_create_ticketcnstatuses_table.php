<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketcnstatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticketcnstatuses', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();


            $table->integer('created_by');
            $table->integer('updated_by');

           
            $table->string('STATUS');
            $table->boolean('active')->default(true);
            
            $table->integer('aa')->nullable();
            $table->integer('bb')->nullable();
            $table->string('cc')->nullable();
            $table->string('dd')->nullable();


            $table->longText('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticketcnstatuses');
    }
}

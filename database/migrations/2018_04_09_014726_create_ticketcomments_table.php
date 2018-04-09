<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketcommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticketcomments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('ticket_no');

            $table->longText('comment');
            $table->boolean('active')->default(true);
            
            $table->integer('location_id')->nullable();
            $table->integer('ticket_type_id')->nullable();
            $table->integer('QUOTE_ID')->nullable();
            $table->string('ORDER_ID', 30)->nullable();
             
            $table->integer('user1')->nullable();
            $table->integer('group1')->nullable();
            $table->integer('user2')->nullable();
            $table->integer('group2')->nullable();
            $table->string('user3')->nullable();
            $table->string('user4')->nullable();


            $table->integer('aa')->nullable();
            $table->integer('bb')->nullable();
            $table->string('cc')->nullable();
            $table->string('dd')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticketcomments');
    }
}

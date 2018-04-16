<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTickettype2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickettype2s', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('ticket_no');

            $table->longText('comment')->nullable();
            $table->boolean('active')->default(true);

            $table->float('amount', 8, 2)->nullable();

            $table->string('error',30)->nullable();
            $table->string('errorcause',30)->nullable();
            
            $table->integer('ticket_type_id')->nullable();
            $table->integer('QUOTE_ID')->nullable();
            $table->string('ORDER_ID', 30)->nullable();
            $table->longText('description')->nullable();
           
            $table->integer('aa')->nullable();
            $table->integer('bb')->nullable();
            $table->integer('cc')->nullable();
            $table->integer('dd')->nullable();
            $table->string('aaa')->nullable();
            $table->string('bbb')->nullable();
            $table->string('ccc')->nullable();
            $table->string('ddd')->nullable();


            $table->integer('ee')->nullable();
            $table->integer('ff')->nullable();
            $table->string('eee')->nullable();
            $table->string('fff')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickettype2s');
    }
}

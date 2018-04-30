<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTickettype4sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickettype4s', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('ticket_no');

            $table->longText('comment')->nullable();
            $table->boolean('active')->default(true);

            $table->float('amount', 8, 2)->nullable();
            $table->string('error',30)->nullable();

            $table->boolean('boola')->default(false);
            $table->boolean('boolb')->default(false);


            $table->string('errorcause',30)->nullable();
            
            $table->integer('ticket_type_id')->nullable();
            $table->integer('QUOTE_ID')->nullable();
            $table->string('ORDER_ID', 30)->nullable();
            $table->longText('issues')->nullable();
            $table->longText('officeuse')->nullable();
           
            $table->integer('astatus')->nullable();
            $table->integer('auser')->nullable();
            $table->integer('agroup')->nullable();
            
            $table->integer('aa')->nullable();
            $table->integer('bb')->nullable();
            $table->integer('cc')->nullable();
            $table->integer('dd')->nullable();
            $table->integer('ee')->nullable();
            $table->integer('ff')->nullable();
            $table->string('aaa')->nullable();
            $table->string('bbb')->nullable();
            $table->string('ccc')->nullable();
            $table->string('ddd')->nullable();
            $table->string('eee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickettype4s');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTickettype3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickettype3s', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('ticket_no');

            $table->longText('comment')->nullable();
            $table->boolean('active')->default(true);

            $table->float('amount', 8, 2)->nullable();

            $table->string('error',30)->nullable();

            $table->boolean('builderorcustomer')->default(false);
            $table->boolean('factory')->default(false);
            $table->boolean('service')->default(false);
            $table->boolean('customerservice')->default(false);
            $table->boolean('sales')->default(false);
            $table->boolean('estimating')->default(false);
            $table->boolean('transport')->default(false);
            $table->boolean('supplier')->default(false);
            $table->boolean('other')->default(false);
            $table->boolean('aaboolean')->default(false);
            $table->boolean('bbbolean')->default(false);
            $table->boolean('ccboolean')->default(false);

            $table->string('errorcause',30)->nullable();
            
            $table->integer('ticket_type_id')->nullable();
            $table->integer('QUOTE_ID')->nullable();
            $table->string('ORDER_ID', 30)->nullable();
            $table->longText('issues')->nullable();
            $table->longText('officeuse')->nullable();
           
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
        Schema::dropIfExists('tickettype3s');
    }
}

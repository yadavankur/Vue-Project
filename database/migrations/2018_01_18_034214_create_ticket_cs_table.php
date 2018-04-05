<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketCsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_cs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('ticket_no');
            $table->integer('ticket_type_id')->nullable();
            $table->integer('QUOTE_ID');
            $table->integer('location_id');
            $table->string('ORDER_ID', 30);
            $table->longText('description')->nullable();
            $table->longText('comment')->nullable();
            $table->string('status')->nullable();
            $table->integer('error_id')->nullable();
            $table->string('approval_status')->nullable();
            $table->integer('user_id');
            $table->integer('allocated_user_id')->nullable();;
            $table->integer('managed_user_id')->nullable();;
            $table->integer('GROUP_ID')->nullable();;
            $table->boolean('active')->default(true); 
            $table->string('CONTACT_PERSON')->nullable();;
            $table->string('CONTACT_EMAIL')->nullable();
            $table->string('CONTACT_PHONE')->nullable();
            $table->string('contact_best_method')->nullable();
            $table->string('CUST_ID')->nullable();
            $table->string('CUST_NAME')->nullable();
            $table->string('SALES_REP')->nullable();
            $table->string('V6_SUPERVISOR')->nullable();
            $table->string('ORDER_STATUS')->nullable();
            $table->string('PRICE')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_cs');
    }
}

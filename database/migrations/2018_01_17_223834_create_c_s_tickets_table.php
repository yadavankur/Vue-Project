<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCSTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_s_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('ticket_no');
            $table->integer('ticket_type_id')->nullable();;
            $table->integer('quote_id');
            $table->integer('location_id');
            $table->string('order_id', 30);
            $table->longText('description')->nullable();
            $table->longText('comment')->nullable();
            $table->string('status')->nullable();
            $table->integer('error_id')->nullable();
            $table->string('approval_status')->nullable();
            $table->integer('user_id');
            $table->integer('allocated_user_id')->nullable();;
            $table->integer('managed_user_id')->nullable();;
            $table->integer('group_id')->nullable();;
            $table->boolean('active')->default(true); 
            $table->string('contact_person')->nullable();;
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_best_method')->nullable();

            $table->string('cust_id')->nullable();
            $table->string('cust_name')->nullable();
            $table->string('sales_rep')->nullable();
            $table->string('v6_supervisor')->nullable();
            $table->string('order_status')->nullable();
            $table->string('price')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('c_s_tickets');
    }
}

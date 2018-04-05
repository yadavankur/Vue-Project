<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TicketstatusModify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticketstatuses', function (Blueprint $table) {

            $table->integer('aa')->nullable()->change();
            $table->integer('bb')->nullable()->change();
            $table->string('cc')->nullable()->change();
            $table->string('dd')->nullable()->change();
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticketstatuses', function (Blueprint $table) {
            //
        });
    }
}

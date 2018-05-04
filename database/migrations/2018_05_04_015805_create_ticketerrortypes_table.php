<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketerrortypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticketerrortypes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('ticket_type_id')->nullable();

            $table->longText('comment')->nullable();
            $table->boolean('active')->default(true);

            $table->string('errorcode',30)->nullable();

            $table->integer('aa')->nullable();
            $table->integer('bb')->nullable();
            $table->integer('cc')->nullable();

            $table->string('aaa')->nullable();
            $table->string('bbb')->nullable();
            $table->string('ccc')->nullable();

            $table->boolean('boola')->default(false);
            $table->boolean('boolb')->default(false);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticketerrortypes');
    }
}

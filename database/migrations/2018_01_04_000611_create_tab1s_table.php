<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTab1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tab1s', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('p_id');
$table->string('nam');
$table->string('lik')->nullable();
$table->string('cmt')->nullable();
$table->boolean('isActive')->default(false);
$table->boolean('active')->default(true);
$table->integer('isShow')->default(false);
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
        Schema::dropIfExists('tab1s');
    }
}

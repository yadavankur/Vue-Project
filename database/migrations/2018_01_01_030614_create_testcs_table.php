<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testcs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->boolean('active')->default(true);    
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0); 

            $table->integer('p_id');
            $table->string('nam');
            $table->string('lik')->nullable();
            $table->string('cmt')->nullable();
            $table->boolean('isActive')->default(false);
            $table->integer('isShow')->default(false);

            $table->integer('quote_id');
$table->integer('location_id');
$table->string('order_id', 30);
$table->integer('service_id');
$table->integer('activity_id');
$table->string('comment')->nullable();
$table->string('name');
$table->integer('tkt_no');
$table->integer('tkt_id');
$table->integer('parent_id');
$table->boolean('can_edit')->default(false);
$table->boolean('is_admin')->default(false);
$table->boolean('is_root')->default(false);
$table->string('aa');
$table->string('bb');
$table->string('cc');
$table->string('dd');
$table->string('ee');
$table->string('ff');
$table->string('gg');
$table->string('hh');
$table->string('ii')->nullable();
$table->string('jj')->nullable();
$table->string('kk')->nullable();
$table->string('ll')->nullable();
$table->integer('a');
$table->integer('b');
$table->integer('c');
$table->integer('d');
$table->integer('e');
$table->integer('f')->default(0);;
$table->integer('g')->default(0);;
$table->integer('h')->default(0);;
$table->integer('i');
$table->integer('j');
$table->boolean('aaa')->default(false);
$table->boolean('bbb')->default(false);
$table->boolean('ccc')->default(false);
$table->boolean('ddd')->default(false);
$table->boolean('eee')->default(true);
$table->boolean('fff')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('testcs');
    }
}

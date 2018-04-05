<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupResourcePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_resource_permission', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id');
            $table->integer('resource_type_id');
            $table->integer('resource_id');
            $table->integer('permission_id');
            $table->boolean('active')->default(true);              
            $table->timestamps();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0); 
            $table->unique(['group_id', 'resource_type_id', 'resource_id', 'permission_id'], 'grrp_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_resource_permission');
    }
}

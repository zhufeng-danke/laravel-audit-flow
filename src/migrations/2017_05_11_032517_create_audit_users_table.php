<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('audit_node_id')->index()->comment('节点ID');
            $table->unsignedInteger('origin_user_id')->index()->comment('用户原始ID');
            $table->string('name', 120)->comment('审核人姓名');
            $table->unsignedInteger('order')->nullable()->comment('审核顺序');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_users');
    }
}

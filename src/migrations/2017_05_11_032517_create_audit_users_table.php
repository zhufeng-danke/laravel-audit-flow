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
            $table->unsignedInteger('audit_flow_id')->index()->comment('审核流ID');
            $table->unsignedInteger('audit_node_id')->index()->comment('节点ID');
            $table->unsignedInteger('audit_associated_user_information_id')->index()->comment('audit_associated_user_informations表ID');
            $table->unsignedTinyInteger('order')->default(0)->comment('审核次序，值越小越先审核');
            $table->unsignedInteger('creator_id')->index()->comment('创建者ID');
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

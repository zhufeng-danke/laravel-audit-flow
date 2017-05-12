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
            $table->unsignedInteger('audit_associated_user_information_id')->index()->comment('用户原始ID');
            $table->unsignedTinyInteger('order')->default(0)->comment('审核次序，值越小越先审核');
            $table->unsignedTinyInteger('current_action')->default(0)->comment('审核人当前最新的行为，默认0，未处理；1，通过；2，退回上步；3，否决，终止审核流；4，撤销审核人行为');
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

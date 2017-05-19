<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bill_id', 60)->comment('单据ID');
            $table->unsignedInteger('audit_flow_id')->index()->comment('审核流ID');
            $table->unsignedInteger('audit_node_id')->index()->comment('节点ID');
            $table->unsignedInteger('audit_user_id')->index()->comment('审核人ID');
            $table->unsignedTinyInteger('action')->default(0)->comment('审核人行为，默认0，未处理；1，通过；2，退回上步；3，否决，终止审核流；4，撤销审核人行为');
            $table->text('comment')->nullable()->comment('审核人评论');
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
        Schema::dropIfExists('audit_records');
    }
}

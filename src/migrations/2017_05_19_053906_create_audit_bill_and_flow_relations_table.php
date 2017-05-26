<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditBillAndFlowRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_bill_and_flow_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bill_id',100)->index()->default('')->comment('单据ID');
            $table->unsignedInteger('audit_flow_id')->index()->comment('审核流ID');
            $table->unsignedInteger('audit_bill_type_id')->index()->comment('审核类型ID');
            $table->unsignedInteger('creator_id')->index();
            $table->tinyInteger('status')->default(1)->comment('审核流状态：1，正常；0，终止。');
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
        Schema::dropIfExists('audit_bill_and_flow_relations');
    }
}

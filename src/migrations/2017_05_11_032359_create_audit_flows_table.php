<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_flows', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bill_id', 60)->comment('单据ID');
            $table->unsignedInteger('audit_bill_type_id')->index()->comment('单据类型ID');
            $table->string('title', 60)->comment('审核流名称');
            $table->text('description')->nullable()->comment('审核流描述');
            $table->tinyInteger('status')->default(0)->comment('审核流状态');
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
        Schema::dropIfExists('audit_flows');
    }
}

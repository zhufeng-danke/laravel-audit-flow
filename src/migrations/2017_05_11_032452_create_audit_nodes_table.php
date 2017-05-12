<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_audit_node_id')->default(0)->index()->comment('父节点ID');
            $table->unsignedInteger('audit_flow_id')->index()->comment('审核流ID');
            $table->string('title', 120)->comment('节点标题');
            $table->text('description')->nullable()->comment('节点描述');
            $table->unsignedInteger('step')->nullable()->comment('第N步节点');
            $table->unsignedInteger('audit_type')->default(1)->comment('审核类型：默认1，全通过；2，至少一人通过');
            $table->tinyInteger('is_end_flow')->default(0)->comment('审核流终节点，默认0，否；1，是；');
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
        Schema::dropIfExists('audit_nodes');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditBillTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_bill_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 120)->comment('资源名称');
            $table->text('description')->nullable()->comment('描述');
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
        Schema::dropIfExists('audit_bill_types');
    }
}

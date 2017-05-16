<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditAssociatedUserInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_associated_user_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('origin_user_id')->index()->comment('业务中用户ID');
            $table->string('name',60)->comment('姓名');
            $table->string('email',255)->nullable()->comment('邮箱');
            $table->string('phone',13)->nullable()->comment('手机号');
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
        Schema::dropIfExists('audit_associated_user_informations');
    }
}

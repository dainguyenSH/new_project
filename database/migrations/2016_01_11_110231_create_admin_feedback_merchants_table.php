<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminFeedbackMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_feedback_merchants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchant_feedback_admins_id');
            $table->integer('admins_id');
            $table->text('messages')->nullable()->comment('noi dung admin gui cho merchants'); 
            $table->integer('status')->default(0)->comment('0 la dang cho xu ly, 1 la da xu ly, 2 la huy'); 
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
        Schema::drop('admin_feedback_merchants');
    }
}

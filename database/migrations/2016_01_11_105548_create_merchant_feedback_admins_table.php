<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantFeedbackAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('merchant_feedback_admins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchants_id');
            $table->text('messages')->nullable();
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
        Schema::drop('merchant_feedback_admins');
    }
}

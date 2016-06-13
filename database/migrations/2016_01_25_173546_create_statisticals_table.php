<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('statisticals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchants_id');
            $table->integer('year');
            $table->text('messages')->comment('thong ke tin nhan');
            $table->text('feedbacks')->comment('thong ke feedback ');
            $table->integer('status')->default(1);
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
        Schema::drop('statisticals');
    }
}

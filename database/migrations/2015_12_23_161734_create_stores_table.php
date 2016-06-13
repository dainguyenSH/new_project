<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchants_id');
            $table->string('user_name')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('password_confirmation')->nullable();
            $table->integer('active')->default(1)->comment('1 la active, 0 la bi khoa, thang nay khong phai active');  
            $table->rememberToken();

            $table->string('store_name')->nullable()->comment('ten cua hang'); 
            $table->string('address')->nullable()->comment('dia chi cua hang');
            $table->string('mobile')->nullable();
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
        Schema::drop('stores');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customers_code')->unique()->nullable()->comment('Ma khach hang');
            // $table->string('user_name')->unique();
            $table->string('email')->unique();
            $table->string('token')->unique();
            // $table->string('password')->nullable();
            // $table->string('password_confirmation')->nullable();
            $table->integer('active')->default(1)->comment('0:Unactive,1:Active');
            $table->string('facebook_id')->unique();
            // $table->string('google_token')->nullable();
            $table->string('device_token')->nullable();
            $table->integer('device_type')->nullable()->comment('1:APPLE,2:ANDROID');
            $table->string('name')->nullable(); //ho ten
            $table->string('gender', '10')->nullable();
            $table->string('work')->nullable(); //ho ten
            $table->string('avatar')->nullable();
            $table->date('birthday')->nullable();
            $table->string('location')->nullable();
            // $table->string('province')->nullable(); //tinh thanh pho
            // $table->string('district')->nullable(); //quan huyen 
            $table->string('mobile')->nullable();
            $table->integer('count_deals')->default(0)->comment('tong so deals moi');
            $table->integer('count_notifications')->default(0)->comment('tong so notifications');
            // $table->rememberToken();
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
        Schema::drop('customers');
    }
}

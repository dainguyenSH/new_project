<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable(); 
            $table->string('avatar')->nullable();
            $table->string('user_name')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->integer('role')->default(1)->comment('1 full quyen, 2 quyen ... '); 
            $table->text('info')->nullable()->comment('luu thong tin khac');
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
        Schema::drop('admins');
    }
}

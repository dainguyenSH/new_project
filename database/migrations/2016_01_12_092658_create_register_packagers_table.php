<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterPackagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_packagers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchants_id')->comment('Id merchant');
            $table->integer('package')->comment('luu id goi lay trong bang optionvalues');
            $table->integer('month')->comment('luu id thang lay trong bang optionvalues');;
            $table->string('name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->text('content')->nullable();
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
        Schema::drop('register_packagers');
    }
}

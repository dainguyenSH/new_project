<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('merchants_id');
            $table->integer('history_achievements_id')->nullable()->comment('null: non-transaction, not null: transaction');
            $table->integer('deals_id')->nullable();
            $table->text('content')->nullable(); 
            $table->integer('type')->default(0)->comment('1 la doi diem, 2 la tich diem, 3 la tich tem, 4 la doi tem, 0 la co deal moi'); 
            $table->integer('count')->default(0)->comment('tong so nguoi da gui thanh cong');
            $table->integer('percentage')->default(0)->comment('phan tram tang giam so voi lan gui truoc');
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
        Schema::drop('notifications');
    }
}

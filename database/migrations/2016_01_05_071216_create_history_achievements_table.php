<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_achievements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customers_id');
            $table->integer('stores_id')->nullable();
            $table->string('order_id')->nullable(); // mã hóa đơn (do thu ngân nhập)
            $table->integer('type')->default(0)->comment('1 la doi diem, 2 la tich diem, 3 la tich tem, 4 la doi tem, 0 la default'); 
            $table->integer('change_points')->nullable();

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
        Schema::drop('history_achievements');
    }
}

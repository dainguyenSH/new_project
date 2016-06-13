<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_merchants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customers_id');
            $table->integer('merchants_id');
            // $table->string('avatar')->nullable()->comment('avatar cua customer');
            $table->string('front_card_image')->nullable()->comment('măt trước ảnh card');
            $table->string('back_card_image')->nullable()->comment('măt sau ảnh card');
            $table->string('barcode')->nullable(); 
            $table->float('point')->default(0)->comment('tổng điểm đã từng tích lũy');
            $table->float('current_point')->default(0)->comment('Điểm hiện tại. Nếu đổi điểm sẽ trừ vào điểm này');
            $table->integer('level')->nullable();
            $table->integer('status')->default(1)->comment('1 là đang active , 0 là unactive');
            $table->text('info')->nullable(); // luu thong tin khac
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
        Schema::drop('customer_merchants');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customers_id');
            $table->integer('barcode');
            $table->string('name')->comment('ten cua merchant');
            $table->string('logo')->nuluable()->comment('logo cua merchant');
            $table->string('front_card_image')->nuluable()->comment('mat truoc cua the');
            $table->string('back_card_image')->nuluable()->comment('mat sau cua the');
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
        Schema::drop('customer_cards');
    }
}

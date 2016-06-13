<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('merchants_id');
            $table->string('name');
            $table->text('description');
            $table->text('apply_objects')->nullable()->comment('doi tuong ap dung, luu kieu json mang id cac doi tuong ap dung');
            $table->datetime('start_day');
            $table->datetime('end_day');
            $table->string('image_avatar');
            $table->float('radio_image')->default(1)->comment('ty le anh chieu cao : chia chieu rong');
            $table->text('image_content')->nullable()->comment('luu 15 cai anh');
            $table->text('stores')->nullable()->comment('luu kieu json cac store khuyen mai, mac dinh la tat ca cac store cua merchant'); 
            $table->string('slug')->nullable()->comment('luu url duong dan');
            $table->integer('status')->default(3)->comment('1 la active , 0 la chua active, 2 la het han, 3 la editable'); 
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
        Schema::drop('deals');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionvaluesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('optionvalues', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('options_id');
            $table->string('name');
            $table->text('info')->nullable(); //save type json
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
        Schema::drop('optionvalues');
    }
}

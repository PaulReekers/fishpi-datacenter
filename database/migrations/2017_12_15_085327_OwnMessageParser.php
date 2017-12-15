<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OwnMessageParser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('text');
            $table->longText('attachment');
        });

        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('text');
            $table->longText('attachment');
            $table->integer('question_id');
        });

        Schema::create('option_question', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('question_id');
            $table->integer('option_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('questions');
        Schema::drop('options');
        Schema::drop('option_question');
    }
}

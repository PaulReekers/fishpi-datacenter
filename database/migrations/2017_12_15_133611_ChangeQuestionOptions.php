<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeQuestionOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->integer('to_question_id');
        });
        Schema::drop('option_question');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->dropColumn('to_question_id');
        });
        Schema::create('option_question', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('question_id');
            $table->integer('option_id');
        });
    }
}

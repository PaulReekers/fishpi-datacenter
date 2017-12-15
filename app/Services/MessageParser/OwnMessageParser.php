<?php

namespace App\Services\MessageParser;

use App\Services\MessageParser\MessageParser;
use App\Services\MessageParser\MessageParserInterface;
use Log;
use App\Option;
use App\Question;
use DB;

class OwnMessageParser extends MessageParser implements MessageParserInterface
{

  public function __construct()
  {

  }

  /**
   * Handle message
   * @param  Int    $from
   * @param  String $text
   * @return Object
   */
  public function handle($from, $text, $quickReply)
  {
    $text = "What tha hack are you talking about!";
    $quickReplies = [];

    $question = false;
    if ($quickReply && isset($quickReply["payload"])) {
      $optionId = $quickReply["payload"];
      $option = Option::find($optionId);

      if ($option) {
        $question = Question::find($option->to_question_id);
      }
    }

    if (!$question) {

      $question = DB::table("questions")->select('id')
        ->whereNotIn('id',function($query){
          $query->select('to_question_id')->from('options');
        })->first();
      if ($question) {
        $question = Question::find($question->id);
      }
    }

    if ($question) {
      $text = $question->text;
      foreach ($question->options()->get() as $option) {
        $quickReplies[ $option->id ] = $option->text;
      }
    }

    $this->responseText = $text;
    $this->responseQuickReplies = $quickReplies;
  }
}
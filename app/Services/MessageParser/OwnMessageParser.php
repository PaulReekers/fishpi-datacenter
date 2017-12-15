<?php

namespace App\Services\MessageParser;

use App\Services\MessageParser\MessageParser;
use App\Services\MessageParser\MessageParserInterface;
use Log;
use App\Option;
use App\Question;

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
        $question = Question::whereHas('parentOptions', function($q) use ($option) {
          $q->where('option_id', '=', $option->id);
        })->with('options')->first();
      }
    }

    if (!$question) {
      $question = Question::whereDoesntHave('parentOptions')->with('options')->first();
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
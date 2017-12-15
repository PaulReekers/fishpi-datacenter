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
    $text = "That is not an answer I was looking for!";
    $quickReplies = [];

    if ($quickReply && isset($quickReply["payload"])) {
      $optionId = $quickReply["payload"];
      $option = Option::find($optionId);

      $question = Question::has('parentOptions', '=', $option->id)->with('options')->first();

      $text = $question->text;
      foreach ($question->options()->get() as $option) {
        $quickReplies[ $option->id ] = $option->text;
      }
    }

    $this->responseText = $text;
    $this->responseQuickReplies = $quickReplies;
  }
}
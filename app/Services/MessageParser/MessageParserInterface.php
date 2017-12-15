<?php

namespace App\Services\MessageParser;

interface MessageParserInterface
{

  /**
   * Handle message
   * @param  Int    $from
   * @param  String $text
   * @return Object
   */
  public function handle($from, $text, $quickReply);
  public function getResponseText();
  public function getResponseAction();
  public function getResponseActionParams();

}
<?php

namespace App\Services\MessageParser;

class MessageParser implements MessageParserInterface
{
  protected $responseText = false;

  protected $responseQuickReplies = [];

  protected $responseAction = false;

  protected $responseActionParams = [];

  public function handle($from, $text)
  {
    return $text;
  }

  public function getResponseText()
  {
    return $this->responseText;
  }

  public function getResponseAction()
  {
    return $this->responseAction;
  }

  public function getResponseActionParams()
  {
    return $this->responseActionParams;
  }

  public function getResponseQuickReplies()
  {
    return $this->responseQuickReplies;
  }
}
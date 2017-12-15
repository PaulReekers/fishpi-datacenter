<?php

namespace App\Services\MessageParser;

class MessageParser implements MessageParserInterface
{
  protected $responseText = false;

  protected $responseQuickReplies = [];

  protected $responseAction = false;

  protected $responseActionParams = [];

  protected $responseImage = false;

  public function handle($from, $text, $quickReply)
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

  public function getResponseImage()
  {
    return $this->responseImage;
  }
}
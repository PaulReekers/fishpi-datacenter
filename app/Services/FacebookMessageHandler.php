<?php

namespace App\Services;

use App\Services\MessageParser\APIAIMessageParser;
use App\Services\MessageParser\GoogleImageParser;
use App\Services\MessageParser\OwnMessageParser;
use App\Services\ActionRunner\FishPIActionRunner;
use App\Services\FacebookMessageResponseSender;
use Log;

class FacebookMessageHandler
{

  private $parser = null;
  private $imageParser = null;
  private $sender = null;
  private $runner = null;

  public function __construct(
    FacebookMessageResponseSender $sender,
    OwnMessageParser $parser,
    GoogleImageParser $imageParser,
    FishPIActionRunner $runner)
  {
    $this->parser = $parser;
    $this->imageParser = $imageParser;
    $this->sender = $sender;
    $this->runner = $runner;
  }

  /**
   * Handle incoming messages
   * @param  Array $incomingMessages
   */
  public function handle($incomingMessages)
  {
    if (!$incomingMessages) {
      Log::info("no incoming message");
      return;
    }

    Log::info(json_encode($incomingMessages));
    if (!is_array($incomingMessages)) {
      Log::notice('incoming message not an array');
      return;
    }
    foreach ($incomingMessages as $key => $value) {
      $this->handleMessages($value);
    }
  }

  /**
   * Handle message
   * @param  Array $value
   */
  private function handleMessages($value)
  {
    if (!isset($value["messaging"]) || !is_array($value["messaging"])) {
      Log::notice('missing messaging object');
      return;
    }
    foreach ($value["messaging"] as $message) {
      $this->handleMessage($message);
    }
  }

  /**
   * Handle message
   * @param  Array  $message
   */
  private function handleMessage($message)
  {
    if (
      !isset($message["message"]) ||
      (
        isset($message["message"]["is_echo"]) &&
        $message["message"]["is_echo"]
      )
    ) {
      Log::notice('echo, not send to sender');
      return;
    }
    if (
      !isset($message["sender"]) ||
      !isset($message["sender"]["id"])
    ) {
      Log::notice('missing sender id');
      return;
    }

    if (isset($message["message"]["text"])) {
      $quickReply = false;
      if (isset($message["message"]["quick_reply"])) {
        $quickReply = $message["message"]["quick_reply"];
      }
      $this->checkMessage($message["sender"]["id"], $message["message"]["text"], $quickReply);
    }

    if (isset($message["message"]["attachments"])) {
      foreach ($message["message"]["attachments"] as $attachment) {
        if (
          isset($attachment["type"]) &&
          $attachment["type"]=="image" &&
          isset($attachment["payload"]["url"])
        ) {
          $this->checkImage($message["sender"]["id"], $attachment["payload"]["url"]);
        }
      }
    }
  }

  private function checkImage($from, $image)
  {
    #$this->imageParser->handle($from, $image);
    #if ($text = $this->imageParser->getResponseText()) {
    #  $this->checkMessage($from, $text);
    #}
  }

  /**
   * Check message text
   * @param  Int    $from
   * @param  String $text
   */
  private function checkMessage($from, $text, $quickReply)
  {
    $this->parser->handle($from, $text, $quickReply);
    $this->handleParserResponse($from);
  }

  private function handleParserResponse($from)
  {

    Log::notice($this->parser->getResponseText());

    $responseImages = [];
    $responseTexts = [
      [
        "text" => $this->parser->getResponseText(),
        "quickreplies" => $this->parser->getResponseQuickReplies(),
      ]
    ];

    if ($image = $this->parser->getResponseImage()) {
      $responseImages[] = $image;
    }

    if ($action = $this->parser->getResponseAction()) {
      Log::notice("Try to run: ".$action);

      $params = $this->parser->getResponseActionParams();
      Log::notice(json_encode($params));
      if ($this->runner->callAction($action, $params)) {
        $responseTexts = $this->runner->getResponseTexts();
        $responseImages = $this->runner->getResponseImages();
      }
    }

    foreach ($responseImages as $responseImage) {
      Log::notice("Send image");
      $this->sender->sendImage($from, $responseImage);
    }

    foreach ($responseTexts as $responseText) {
      Log::notice("Send text");
      $this->sender->sendQuote($from, $responseText);
    }
  }
}

<?php

namespace App\Services\MessageParser;

use App\Services\MessageParser\MessageParser;
use App\Services\MessageParser\MessageParserInterface;
use Log;
#use Google\Cloud\Vision\VisionClient;

class GoogleImageParser extends MessageParser implements MessageParserInterface
{
  public function __construct()
  {
  }

  public function handle($from, $image, $quickReply)
  {

  }
}
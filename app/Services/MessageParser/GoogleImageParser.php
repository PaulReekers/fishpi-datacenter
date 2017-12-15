<?php

namespace App\Services\MessageParser;

use App\Services\MessageParser\MessageParser;
use App\Services\MessageParser\MessageParserInterface;
use Log;
use Google\Cloud\Vision\VisionClient;

class GoogleImageParser extends MessageParser implements MessageParserInterface
{
  public function __construct()
  {
    $projectId = 'fishpi-161909';
    $this->vision = new VisionClient([
      'projectId' => $projectId,
      'keyFilePath' => base_path(env('GOOGLE_KEY_FILE'))
    ]);
  }

  public function handle($from, $image, $quickReply)
  {
    Log::notice("Image to handle: ".$image);
    try {
      $imageData = file_get_contents($image);

      # Prepare the image to be annotated
      $image = $this->vision->image($imageData, [
          'LABEL_DETECTION'
      ]);

      # Performs label detection on the image file
      $labels = $this->vision->annotate($image)->labels();

      $labelNames = [];
      foreach ($labels as $label) {
        $labelNames[] = $label->description();
      }

      Log::notice("Labels found ".implode(", ", $labelNames));
      $this->responseText = 'Here is a picture of a: '.implode(", ", $labelNames);

    } catch (\Exception $e) {
      Log::error($e->getMessage());
    }
  }
}
<?php

namespace App\Services;

use App\Services\FacebookMessageResponseSender;
use App\FishData;
use Log;

class MessageParser
{

  public function handle($text)
  {
    if (stripos($text, "temp") !== false) {
      if (stripos($text, "air") !== false) {
        return "The current air temperature is: ".$this->getCurrentAirTemp();
      }
      if (stripos($text, "water") !== false) {
        return "The current water temperature is: ".$this->getCurrentWaterTemp();
      }
      return "Do you want the temperature of water or air?";
    }

    $helpList = ["help", "hi", "hoi", "?"];
    foreach ($helpList as $help) {
      if (stripos($text, $help) !== false) {
        return "Hi, for now I can give u the air or water temperature if you ask for it";
      }
    }

    return false;
  }

  /**
   * Get current water temp
   * @return [type] [description]
   */
  private function getCurrentWaterTemp()
  {
    $data = FishData::orderBy('time', 'desc')->firstOrFail();
    if (!$data) {
      return "n/a";
    }
    return $data["water"];
  }

  /**
   * Get current air temp
   * @return [type] [description]
   */
  private function getCurrentAirTemp()
  {
    $data = FishData::orderBy('time', 'desc')->firstOrFail();
    if (!$data) {
      return "n/a";
    }
    return $data["air"];
  }

}
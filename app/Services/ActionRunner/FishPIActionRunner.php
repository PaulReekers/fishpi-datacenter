<?php

namespace App\Services\ActionRunner;

use App\Command;
use App\Services\ActionRunner\ActionRunner;
use App\FishData;
use Log;

class FishPIActionRunner extends ActionRunner
{

  /**
   * Get current water temp
   * @return [type] [description]
   */
  protected function getTemperature($text, $type = false)
  {
    $temp = $this->getTemperatureFromDb($type);
    return str_replace("*temperature*", $temp, $text);
  }

  /**
   * Set led
   * @param [type] $text   [description]
   * @param [type] $onOrOf [description]
   * @param [type] $led    [description]
   */
  protected function setLed($text, $onOrOf, $led)
  {
    Log::info("Turn led: ".$led." - ".$onOrOf);

    try {
      $command = new Command;
      $command->command = 'setled';
      $command->data = json_encode([
        "led" => $led,
        "onOrOf" => $onOrOf
      ]);
      $command->executed = false;
      $command->save();
    } catch (\Exception $e) {
      Log::info("Db not available");
    }

    return $text;
  }

  /**
   * get temperature from the database
   * @param  String $type
   * @return String
   */
  private function getTemperatureFromDb($type) {
    if ($type) {
      return "n/a";
    }
    try {
      $data = FishData::orderBy('time', 'desc')->firstOrFail();
    } catch (\Exception $e) {
      return $type." temperature n/a";
    }
    return $this->toReadableTemp($data[$type]);
  }

  /**
   * Transform temp to readable temp
   * @param  [type] $temp [description]
   * @return [type]       [description]
   */
  private function toReadableTemp($temp)
  {
    if (!is_numeric($temp)) {
      return $temp;
    }
    $temp = $temp / 1000;
    return round($temp, 2)."º";
  }
}
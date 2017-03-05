<?php

namespace App\Services\ActionRunner;

use App\Command;
use App\Services\ActionRunner\ActionRunner;
use App\FishData;
use Log;

class FishPIActionRunner extends ActionRunner
{

  private $statsTypeList = [
    "Average",
    "Maximum",
    "Minimum"
  ];

  /**
   * Get current water temp
   * @return [type] [description]
   */
  protected function getTemperature($text, $type = false)
  {
    Log::notice('Type: '.$type);
    $temp = $this->getTemperatureFromDb($type);
    return $this->replaceTempInText($text, $temp);
  }

  protected function getTemperatureStats($text, $date, $statsType, $type)
  {
    $temp = $this->getTemperatureStatsFromDb($date, $statsType, $type);
    return $this->replaceTempInText($text, $temp);
  }

  protected function replaceTempInText($text, $temp)
  {
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
   * Get temperature stats from database
   * @param  [type] $date      [description]
   * @param  [type] $statsType [description]
   * @param  [type] $type      [description]
   * @return [type]            [description]
   */
  private function getTemperatureStatsFromDb($date, $statsType, $type)
  {
    if (!in_array($statsType, $this->statsTypeList)) {
      Log::notice("Wrong stats type: ".$statsType);
      return "n/a";
    }
    try {
      $data = FishData::whereDate("time", '=', date('Y-m-d',strtotime($date)));
      switch($statsType) {
        case "Average":
          $temp = $data->avg($type);
        break;
        case "Maximum":
          $temp = $data->max($type);
        break;
        case "Minimum":
          $temp = $data->min($type);
        break;
      }
    } catch (\Exception $e) {
      Log::error($e->getMessage());
      return "n/a";
    }
    return $this->toReadableTemp($temp);
  }

  /**
   * get temperature from the database
   * @param  String $type
   * @return String
   */
  private function getTemperatureFromDb($type) {
    if (!$type) {
      return "n/a";
    }
    try {
      $data = FishData::orderBy('time', 'desc')->firstOrFail();
    } catch (\Exception $e) {
      return "n/a";
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
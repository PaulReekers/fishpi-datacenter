<?php

namespace App\Services\ActionRunner;

use App\FishData;
use App\Command;
use Log;

interface ActionRunnerInterface
{
  public function callAction($action, $params);
}
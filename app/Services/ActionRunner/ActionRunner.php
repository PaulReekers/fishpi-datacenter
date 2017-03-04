<?php

namespace App\Services\ActionRunner;

class ActionRunner implements ActionRunnerInterface
{
  public function callAction($action, $params)
  {
    if (!method_exists($this, $action)) {
      return;
    }

    // call the action and return with the result
    return call_user_func_array(array($this, $action), $params);
  }
}
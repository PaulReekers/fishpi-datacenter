<?php

namespace App\Services\ActionRunner;

class ActionRunner implements ActionRunnerInterface
{

  protected $responseImages = [];
  protected $responseTexts = [];

  public function getResponseTexts()
  {
    return $this->responseTexts;
  }

  public function getResponseImages()
  {
    return $this->responseImages;
  }

  public function callAction($action, $params)
  {
    if (!method_exists($this, $action)) {
      return false;
    }

    // call the action and return with the result
    return call_user_func_array(array($this, $action), $params);
  }
}
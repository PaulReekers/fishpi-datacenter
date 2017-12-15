<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacebookMessageHandler;
use Log;
use App\Question;
use App\Option;

class MessageController extends Controller
{

  private $handler = null;

  public function __construct()
  {
    header("Access-Control-Allow-Origin: *");
  }

  /**
   * Facebook check webhook
   * @param  Request $request [description]
   * @return String
   */
  public function storeQuestion(Request $request, $id = false)
  {
    $text = $request->input('text', '');
    $attachment = $request->input('attachment', '');

    $data = [
      'text' => $text,
      'attachment' => $attachment
    ];

    if ($id) {
      $question = Question::find($id);
      if (!$question) {
        return response(['error' => 'Question not found'], 400);
      }
      $question->fill($data);
      $question->save();
    } else {

      $option = $request->input('option', false);

      // check if we already have an start question then we dont allow adding a new question without an option
      if (Question::whereDoesntHave('parentOptions')->first() && !$option) {
        return response(['error' => 'We already have a first question you need to add an option as a parent'], 400);
      }

      if ($option && !Option::find($option)) {
        return response(['error' => 'Option not found'], 400);
      }

      $question = Question::create($data);
      if ($option) {
        $question->parentOptions()->attach($option);
      }
    }

    return [
      "status" => "oke",
      "question" => $question->toArray()
    ];
  }

  public function storeOptionQuestion(Request $request, $id = false, $option = false)
  {
    if (!$id) {
      return response(['error' => 'No id given'], 400);
    }
    $question = Question::find($id);
    if (!$question) {
      return response(['error' => 'Question not found'], 400);
    }

    $text = $request->input('text', '');
    $attachment = $request->input('attachment', '');

    $data = [
      'text' => $text,
      'attachment' => $attachment,
      'question_id' => $id
    ];

    if ($option) {
      $option = Option::find($option);
      if (!$option) {
        return response(['error' => 'Option not found'], 400);
      }
    } else {
      $option = Option::create();
    }
    $option->fill($data);
    $option->save();

    return [
      "status" => "oke",
      "option" => $option->toArray()
    ];
  }

  /**
   * Receive webhook data
   * @param  Request $request
   */
  public function getQuestion($id = false)
  {
    if ($id) {
      $question = Question::find($id);
    } else {
      $question = Question::whereDoesntHave('parentOptions')->first();
    }

    if (!$question) {
      return response(['error' => 'Question not found'], 400);
    }

    return [
      "status" => "oke",
      "question" => $question->toArray(),
      "options" => $question->options()->get()->toArray(),
      "parentOptions" => $question->parentOptions()->get()->toArray()
    ];
  }
}
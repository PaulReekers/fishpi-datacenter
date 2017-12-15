<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacebookMessageHandler;
use Log;
use App\Question;
use App\Option;
use DB;

class MessageController extends Controller
{

  private $handler = null;

  public function __construct()
  {
  }

  public function all()
  {
    $questions = Question::with('options')->get();
    return $questions->toArray();
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

      $first = Question::where('first', '=', 1)->first();
      // check if we already have an start question then we dont allow adding a new question without an option
      if ($first && !$option) {
        return response(['error' => 'We already have a first question you need to add an option as a parent'], 400);
      }

      if ($option && !$optionModel = Option::find($option)) {
        return response(['error' => 'Option not found'], 400);
      }

      if ($option && $optionModel->to_question_id > 0) {
        return response(['error' => 'Option already in use'], 400);
      }

      if (!$option) {
        $data['first'] = 1;
      }

      $question = Question::create($data);
      if ($option) {
        $optionModel->to_question_id = $question->id;
        $optionModel->save();
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

    $text = $request->input('text', false);
    $attachment = $request->input('attachment', false);
    $to_question_id = $request->input('to_question_id', false);

    $data = [
      'question_id' => $id
    ];

    if ($text) {
      $data['text'] = $text;
    }

    if ($attachment) {
      $data['attachment'] = $attachment;
    }

    if ($to_question_id !== false) {
      $data['to_question_id'] = $to_question_id;
    }

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
      $question = Question::where('first', '=', 1)->first();
    }

    if (!$question) {
      return response(['error' => 'Question not found'], 400);
    }

    return [
      "status" => "oke",
      "question" => $question->toArray(),
      "options" => $question->options()->get()->toArray()
    ];
  }
}
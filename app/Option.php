<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Question;

class Option extends Model
{
	public $timestamps = false;

  protected $fillable = [
      'text',
      'attachment',
      'question_id'
  ];

  public function Question()
  {
    return $this->belongsTo('App\Question');
  }

  public function toQuestion()
  {
    return $this->belongsTo('App\Question', 'id', 'to_question_id');
  }
}

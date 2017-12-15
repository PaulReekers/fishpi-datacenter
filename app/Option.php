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
}

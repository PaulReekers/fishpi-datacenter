<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Option;

class Question extends Model
{
	public $timestamps = false;

  protected $fillable = [
      'text',
      'attachment'
  ];

  public function parentOptions()
  {
    return $this->belongsToMany('App\Option');
  }

  public function options()
  {
    return $this->hasMany('App\Option');
  }

}

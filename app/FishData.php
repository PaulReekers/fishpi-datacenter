<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FishData extends Model
{
    /**
     * A database is normal plural, "fish_data" is both singular and plural,
     * So we have to correct it here
     */
    public $table = 'fish_data';
    public $timestamps = false;

    protected $fillable = [
        'time', 'water', 'air',
    ];
}

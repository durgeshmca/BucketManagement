<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BucketSuggestion extends Model
{
   protected $table = 'bucket_sugesstions';
   protected $with = ['ball'];
    public function ball()
    {
        return $this->belongsTo('App\Ball');
    }
}

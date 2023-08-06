<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ball extends Model
{
   protected $fillable = ['name','volume'];
   public function bucket_suggestion()
    {
        return $this->hasOne('App\BucketSuggestion');
    }
}

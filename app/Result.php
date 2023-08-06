<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Result extends Model
{
    public static function getResult()
    {
       return DB::table('results')
                    ->join('buckets', 'buckets.id', '=', 'results.bucket_id')
                    ->join('balls', 'balls.id', '=', 'results.ball_id')
                    ->select(DB::raw('bucket_id,buckets.name as bucket_name,ball_id,balls.name as ball_name,count(ball_id) as no_of_balls'))
                    ->groupBy('ball_id','bucket_id')
                    ->get();
    }
}

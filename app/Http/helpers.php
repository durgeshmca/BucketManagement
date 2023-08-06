<?php
use Illuminate\Support\Facades\DB;
use App\Result;
use App\Bucket;
function resetDB()
{
    DB::statement("SET foreign_key_checks=0");
    Result::truncate();
    Bucket::query()->update(['empty_volume' => DB::raw('`volume`')]);
    DB::statement("SET foreign_key_checks=1");
}
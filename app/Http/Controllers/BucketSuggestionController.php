<?php

namespace App\Http\Controllers;

use App\Ball;
use App\Bucket;
use App\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class BucketSuggestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all balls and result list
        $resultArray = $this->getResultData();
        $balls = Ball::all(['id', 'name', 'volume']);
        $data = [
            'balls' => $balls,
            'result' =>  $resultArray,
            'suggestion' => ''
        ];
        return view('suggestion.sugestionform', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $balls = Ball::all(['id', 'name', 'volume']);
        $validationData = [];
        foreach ($balls as $ball) {
            $validationData[$ball->name] = 'required|numeric|min:0';
        }
        $request->validate($validationData);
        try {


            $inputBalls = $request->input();
            unset($inputBalls['_token']);

            $allocationResult = $this->allocateBallsToBuckets($inputBalls);
            $resultArray = $this->getResultData();
            $data = [
                'balls' => $balls,
                'suggestions' => $allocationResult,
                'result' => $resultArray
            ];
            return view('suggestion.sugestionform', $data);
        } catch (\Exception $e) {

            return view('suggestion.sugestionform', ['balls' => $balls, 'message' => $e->getMessage()]);
        }
    }

    private function allocateBallsToBuckets($suggestion)
    {
        $ballNames = array_keys($suggestion);
        //get balls in desending order of volume
        $balls = Ball::whereIn('name', $ballNames)->orderBy('volume', 'desc')->get();
        $finalBallsArray = $this->getFinalBallArray($suggestion, $balls);
        $placedBalls = [];
        //get buckets in descending order of volume
        $buckets = Bucket::orderBy('volume', 'desc')->get();
        foreach ($finalBallsArray as $ball) {
            foreach ($buckets as $bucket) {
                Log::info("Placing $ball->name ball into Bucket $bucket->name");
                Log::info("Ball size $ball->volume Empty volume in Bucket $bucket->name : $bucket->empty_volume");
                if ($this->addBall($bucket, $ball)) {
                    Log::info("Placing $ball->name ball into Bucket $bucket->name sucess");
                    array_push($placedBalls, [$bucket->name => $ball->name]);
                    break;
                }
                Log::info("Placing $ball->name ball into Bucket $bucket->name failed");
            }
        }
        $bktbl = [];
        foreach ($placedBalls as $bkt => $bl) {
            $b = key($bl);
            $bal = current($bl);
            $bktbl[$b][$bal] = isset($bktbl[$b][$bal]) ?  $bktbl[$b][$bal] + 1 :  1;
        }
        Log::info($bktbl);
        //get balls
        Log::info('Placed balls:{balls}', ['balls' => $placedBalls]);
        $f = array_map(function ($value) {
            return $value->name;
        }, $finalBallsArray);
        Log::info(["final" => $f]);
        $m = array_map(function ($value) {
            return array_pop($value);
        }, $placedBalls);
        Log::info(["placed" => $m]);
        $missedBalls = $this->getMissedBalls($f, $m);
        Log::info('Missed balls:{balls}', ['balls' => $missedBalls]);
        return [
            'placed' => array_count_values($m),
            'missed' => $missedBalls,
            'current_suggestion' => $bktbl
        ];
    }

    public function addBall($bucket, $ball)
    {
        if ($bucket->empty_volume >= $ball->volume) {
            //add or update ball to result
            $result = new  Result();
            $result->ball_id = $ball->id;
            $result->bucket_id = $bucket->id;
            $result->no_of_balls = 1;
            $result->save();
            //update empty volume
            $bucket->empty_volume = $bucket->empty_volume - $ball->volume;
            $bucket->save();
            return true;
        }
        return false;
    }

    public function getFinalBallArray($sugestion, $balls)
    {
        $final = array();
        foreach ($sugestion as $BallName => $ballCount) {
            //get the object of ball
            $ball = $balls->firstWhere('name', $BallName);
            $final = array_merge($final, array_fill(0, $ballCount, $ball));
        }
        return $final;
    }
    public function getMissedBalls($final, $filled)
    {
        $ac = array_count_values($final);
        $bc = array_count_values($filled);
        $diff = [];
        foreach ($ac as $name => $num) {
            $dif = $ac[$name] - (isset($bc[$name]) ? $bc[$name] : 0);
            $diff[$name] = $dif;
        }
        return $diff;
    }
    public function getResultData()
    {
        $resultArray = array();
        $results = Result::getResult();
        if ($results) {
            $ra = $results->toArray();
            foreach ($ra as $row) {
                $msg = isset($resultArray[$row->bucket_name]) ? $resultArray[$row->bucket_name] : '';
                $resultArray[$row->bucket_name] = $msg . "$row->no_of_balls  $row->ball_name Balls and ";
            }
        }
        return $resultArray;
    }
}

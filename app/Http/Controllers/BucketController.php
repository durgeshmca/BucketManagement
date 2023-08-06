<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Bucket;
use App\Result;

class BucketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buckets = Bucket::all();
        $data = [
            'buckets'=>$buckets
        ];
        return view('buckets.bucketform', $data);
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

        $request->validate([
            'name' => 'required|string|unique:buckets|max:5|min:1',
            'volume' => 'required|numeric|gt:0',
        ]);
        try {
            $name   = $request->input('name');
            $volume = $request->input('volume');

            $bucket = new Bucket();
            $bucket->name = $name;
            $bucket->volume = $volume;
            $bucket->empty_volume = $volume;
            $bucket->save();
            resetDB();
            return redirect("/buckets")->with('message', $bucket->name . ' added');
        } catch (\Exception $e) {
            return redirect("/buckets")->with('message',$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bucket  $bucket
     * @return \Illuminate\Http\Response
     */
    public function show(Bucket $bucket)
    {
        
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bucket  $bucket
     * @return \Illuminate\Http\Response
     */
    public function edit(Bucket $bucket)
    {
        $data = [
            'bucket'=>$bucket,
            'edit'=>1
        ];
        return view('buckets.bucketform', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bucket  $bucket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bucket $bucket)
    {
        $request->validate([
            'name' => 'required|string|max:5|min:1',
            'volume' => 'required|numeric|gt:0',
        ]);
        try {
            $name   = $request->input('name');
            $volume = $request->input('volume');
            $bucket->name = $name;
            $bucket->volume = $volume;
            $bucket->empty_volume = $volume;
            $bucket->save();
            resetDB();
            return redirect("/buckets")->with("message",'Updated');
        } catch (\Exception $e) {
            return redirect("/buckets")->with("message",$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bucket  $bucket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bucket $bucket)
    {
        try{
            resetDB();
            $bucket->delete();
            return redirect('buckets')->with("message",'Success');
        } catch (\Exception $e) {
            return redirect('balls')->with("message",$e->getMessage());
        } 
    }
}

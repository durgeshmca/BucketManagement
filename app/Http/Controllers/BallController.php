<?php

namespace App\Http\Controllers;

use App\Ball;
use Illuminate\Http\Request;

class BallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $balls = Ball::all();
        $data = [
            'balls'=> $balls
        ];
        return view('balls.ballform', $data);
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
            'name' => 'required|string|unique:balls|max:10|min:1',
            'volume' => 'required|numeric|gt:0',
        ]);
        try {
            $name   = $request->input('name');
            $volume = $request->input('volume');
            $ball = new Ball();
            $ball->name = $name;
            $ball->volume = $volume;
            $ball->save();
            resetDB();
            return redirect("/balls")->with('message', $ball->name . ' added');
        } catch (\Exception $e) {

            return view('balls.ballform', ['message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ball  $ball
     * @return \Illuminate\Http\Response
     */
    public function show(Ball $ball)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ball  $ball
     * @return \Illuminate\Http\Response
     */
    public function edit(Ball $ball)
    {
        $data = [
            'ball'=>$ball,
            'edit'=>1
        ];
        return view('balls.ballform', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ball  $ball
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ball $ball)
    {
        $request->validate([
            'name' => 'required|string|max:10|min:1',
            'volume' => 'required|numeric|gt:0',
        ]);
        try {
            $name   = $request->input('name');
            $volume = $request->input('volume');
            $ball->name = $name;
            $ball->volume = $volume;
            $ball->save();
            resetDB();
            return redirect("/balls")->with("message",'Updated');
        } catch (\Exception $e) {

            return redirect("/balls")->with("message",$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ball  $ball
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ball $ball)
    {
        try{
            resetDB();
            $ball->delete();
            return redirect('balls')->with("message",'Success');
        } catch (\Exception $e) {
            return redirect('balls')->with("message",$e->getMessage());
        } 
        
        
    }
}

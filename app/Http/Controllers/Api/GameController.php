<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GameController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $games = Game::select("id", "title", "total_play_count")->withCount("players")->get();
        return response()->json($games, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $game_id = Game::insertGetId([
            "title" => $request->title,
        ]);
        $game = Game::find($game_id);

        return response()->json($game, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show($game_id)
    {
        $game = Game::find($game_id);

        if (!$game)
            return response()->json(['error' => true, 'message' => "Game not found"], 201);

        return response()->json($game, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $game_id)
    {

        $game = Game::find($game_id);

        if (!$game)
            return response()->json(['error' => true, 'message' => "Game not found"], 201);



        $game->update([
            "title" => $request->title,
        ]);


        return response()->json(['success' => true ,'game'=> $game], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy($game_id)
    {

        $game = Game::find($game_id);

        if (!$game)
            return response()->json(['error' => true, 'message' => "Game not found"], 201);

        $game->delete();
        return response()->json(['message' => "Oyun Silindi"], 200);
    }
}

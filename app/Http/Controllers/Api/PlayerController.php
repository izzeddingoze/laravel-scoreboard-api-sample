<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use App\Models\PlayerGamePivot;
use Illuminate\Routing\Route;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = Player::select("id", "name")->withCount("games")->get();
        return response()->json($players, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $player_id = Player::insertGetId([
            "name" => $request->name,
        ]);
        $player = Player::find($player_id);

        return response()->json($player, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function show($player_id)
    {

        $player = Player::find($player_id);

        if (!$player)
            return response()->json(['error' => true, 'message' => "Player not found"], 201);

        return response()->json($player, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $player_id)
    {
        $player = Player::find($player_id);

        if (!$player)
            return response()->json(['error' => true, 'message' => "Player not found"], 201);

        $player->update([
            "name" => $request->name,
        ]);

        return response()->json(['success' => true, 'player'=>$player], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy($player_id)
    {
        $player = Player::find($player_id);

        if (!$player)
            return response()->json(['error' => true, 'message' => "Player not found"], 201);

        $player->delete();

        return response()->json(['message' => "Player removed"], 200);
    }
}

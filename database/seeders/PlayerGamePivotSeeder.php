<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlayerGamePivot;
use App\Models\Game;
use App\Http\Controllers\Api\ScoreBoardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;


class PlayerGamePivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $score_rows = PlayerGamePivot::factory(100)->make();

        foreach ($score_rows as $score_row) {

            $data = [
                "game_id" =>   $score_row->game_id,
                "player_id" =>   $score_row->player_id,
                "score" =>   $score_row->score
            ];
            /**
             * internal api request yaparak üretilen score satırlarını sscoreboard'a ekleyelim
             */
            $request = Request::create('/api/score-board', 'POST', $data);
            app()->handle($request);
        }
    }
}

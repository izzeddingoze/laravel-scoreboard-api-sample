<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlayerGamePivot;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Support\Facades\Http;

class ScoreBoardController extends Controller
{


    public function store(Request $request)
    {

        /**
         * Gelen request'teki game_id lle kayıtlı oyun veya player_id ile kayıtlı oyuncu yok ise başarısız döndür
         * Gelen request'teki game_id ve player_id ikilisi ile PlayerGamePivot  modeli üzerinden arama yap.
         * Eğer bu ikiliyi içeren bir satır var ise o satır üzerinden gerekli işlemleri yap.Yok ise yeni satır oluştur gerekli işlemleri yap
         * Gelen score'u last_score'a kaydet.
         * Ardından  rankları yeniden belirle.
         * o satırda count_of_playing arttır. Oyuncunun o oyunu oynama sayısı.
         *
         */

        if (!Game::find($request->game_id))
            return response()->json(["success" => false, "message" => "Oyun Bulunamadı"], 404);

        if (!Player::find($request->game_id))
            return response()->json(["success" => false, "message" => "Oyuncu Bulunamadı"], 404);


        $player_game_pivot_rows = PlayerGamePivot::where([
            ['game_id', '=', $request->game_id],
            ['player_id', '=', $request->player_id]
        ])->get();


        /**
         * aynı oyunu daha önce aynı oyuncu oynamışsa satırda rankı ve score'u güncelle
         * yeni score eski score'dan yüksekse  score güncelle
         * daha önce bu oyunu oynamamışsa  gelen oyuncu yeni satır ekle scoreboarda
         *
         *  */
        if ($player_game_pivot_rows->count() > 0) {

            $player_game_pivot_row = $player_game_pivot_rows->first();

            if ($player_game_pivot_row->last_score < $request->score) {

                $player_game_pivot_row->update([
                    "last_score" => $request->score
                ]);

                /**
                 * eğer daha iyi bir score alınmışsa scoreboardda rankları güncelle
                 */
                $this->updateRanks($request->game_id);
            }
        } else {
            $inserted_player_game_pivot_row = PlayerGamePivot::insertGetId([
                "game_id" => $request->game_id,
                "player_id" => $request->player_id,
                "last_score" => $request->score,
                "old_rank" => 0,
                "new_rank" => 0,
            ]);

            $player_game_pivot_row = PlayerGamePivot::find($inserted_player_game_pivot_row);

            /**
             * ekleme sonra oluşan yeni scoreboardda rankları güncelle
             */
            $this->updateRanks($request->game_id);
        }


        /**
         * Ekleme veya güncelleme işleminden sonra oyuncunun oyunu oynama sayısını scoreboardda 1 arttır
         */
        $player_game_pivot_row->incrementCountOfPlaying();

        /**
         * Ekleme veya güncelleme işleminden sonra oyunun toplam oynanma sayısını 1 arttır
         */
        Game::find($request->game_id)->incrementTotalPlaycount();


        /**
         * güncelleme sonrası oyun oyuncu ikilisine ait güncel satırı çek
         */
        $player_game_pivot_row = PlayerGamePivot::find($player_game_pivot_row->id);

        return response()->json([
            "success" => true,
            "message" => "Skor eklendi",
            "last_score" => $player_game_pivot_row->last_score,
            "old_rank" => $player_game_pivot_row->old_rank,
            "new_rank" => $player_game_pivot_row->new_rank,
        ], 201);
    }

    public function show($game_id)
    {

        /**
         * Eğer gönderilen game_id'ye ait pivot tabloda data yok ise error,404 döner
         * game_id'ye ait datalar var ise new_rank'a göre sıralayıp döndür
         */

        $player_game_pivot_data = PlayerGamePivot::where("game_id", $game_id);

        if (!$player_game_pivot_data->count() > 0)
            return response()->json(["error" => "Sonuç Bulunamadı"], 404);

        $score_board = $player_game_pivot_data->select("player_id", "new_rank", "last_score")->get()->sortBy("new_rank")->take(25);
        return response()->json($score_board, 200);
    }

    /**
     *
     * bu method gelen game_id ile tüm pivot verileri çeker
     * last_score'a göre sıralayıp bir model koleyksiyonuna atar
     * koleyksiyonu last_score sırasına göre gezip her satırda new_rank'ı old_rank'a çeker ve new_rankı foreach indexine göre yeniden belirler
     */
    public function updateRanks($game_id)
    {

        $all_player_game_pivot_rows = PlayerGamePivot::where("game_id", $game_id)->get()->sortByDesc("last_score");



        $rank = 1;
        foreach ($all_player_game_pivot_rows as $player_game_pivot_row) {

            $old_rank = $player_game_pivot_row->new_rank;
            $new_rank =  $rank++;

            $player_game_pivot_row->update([
                "old_rank" => $old_rank,
                "new_rank" => $new_rank,
            ]);
        }
    }
}

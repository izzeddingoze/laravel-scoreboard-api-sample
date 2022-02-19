<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Game;
use App\Models\Player;

class PlayerGamePivotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        /**
         * Bu factory ile pivot tabloya aktarmak için örnek datalar üretilecek, cerate methodu kullanılmayacak, make kullanılacak
         * aynı player_id ve game_id verisine sahip data gelirse oynanma sayısı arttırılacak skor guncellenecek old ve new rank güncellenecek
         * aynı player_id ve game_id satırı gelmezse yeni  satır eklenecek ve new rank verilecek
         *
         */

        $game_id = Game::all()->pluck("id")->random(1)->first();
        $player_id = Player::all()->pluck("id")->random(1)->first();


        return [
            'player_id' => $player_id,
            'game_id' => $game_id,
            'score' => rand(0, 10000),
        ];
    }
}

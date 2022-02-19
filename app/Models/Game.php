<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $timestamps = true;
    use HasFactory;
    protected $fillable = ["title","total_play_count"];

    public function players()
    {
        /**
         * Pivot(bir nevi tüm oyunlar için ortak scoreboard) tablodan oyunu oynayan oyuncuları getir
         */

        return $this->belongsToMany(
            Player::class,
            'player_game_pivot',
            'player_id',
            'game_id'
        );
    }

    public function incrementTotalPlaycount()
    {
        /**
         *
         * bu oyunun toplam oynanma sayısını 1 arttır
         */
        $this->total_play_count++;
        return $this->save();
    }
}



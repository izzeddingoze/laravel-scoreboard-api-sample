<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    public $timestamps = true;
    use HasFactory;

    protected $fillable= ["name"];

    public function games()
    {
        /**
         * Pivot(bir nevi tüm oyunlar için ortak scoreboard) tablodan bu oyuncunun oynadığı oyunları getir
         */

        return $this->belongsToMany(
            Player::class,
            'player_game_pivot',
            'game_id',
            'player_id'
        );
    }
}

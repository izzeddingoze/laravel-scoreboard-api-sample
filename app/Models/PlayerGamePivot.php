<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerGamePivot extends Model
{

    public $timestamps = true;
    use HasFactory;
    protected $table = 'player_game_pivot';
    protected $fillable = ['game_id', 'player_id', 'old_rank', 'new_rank', 'last_score', 'count_of_playing'];

    public function incrementCountOfPlaying()
    {
        /**
         *
         * Pivot tabloda her satırdaki game_id ve player_id ikilisi benzersiz olacak.
         * Yani her oyuncunun oynadığı her oyun için sadece bir satır veri tutulacak.
         * Tekrardan aynı oyuncu aynı oyunu oynarsa bu tespit edilecek skor eklrken ve o oyunu oynama sayısı bu method ile bir attırılacak
         * ilk oynadığında satır kayıt edildikten sonra bu method bir kere çağırılacak
         *
         */
        $this->count_of_playing++;
        return $this->save();
    }
}

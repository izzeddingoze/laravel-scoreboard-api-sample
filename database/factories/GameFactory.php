<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */



    public function definition()
    {
        return [
            'title' => $this->faker->word(5),
            'total_play_count' => 0,
        ];
    }
}

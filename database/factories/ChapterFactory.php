<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class ChapterFactory extends Factory
{
    use WithFaker;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->randomFloat(3, 2, 30),
            'description' => $this->faker->sentences(2, true),
            'title_id' => $this->faker->word(),
            'code_title' => $this->faker->word(),
        ];
    }
}

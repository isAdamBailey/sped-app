<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class SectionFactory extends Factory
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
            'url' => $this->faker->url(),
            'description' => $this->faker->sentences(2, true),
            'content' => $this->faker->paragraphs(3, true),
            'code_title' => $this->faker->word(),
        ];
    }
}

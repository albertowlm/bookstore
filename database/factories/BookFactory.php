<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
            'publishing_companies' => $this->faker->company(),
            'edition' => $this->faker->randomDigit,
            'year_publication' => $this->faker->year,
            'price' => $this->faker->randomFloat(2, 0, 999.99)
        ];
    }
}
